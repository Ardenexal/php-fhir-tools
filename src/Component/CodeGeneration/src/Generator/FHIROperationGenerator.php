<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\AllowedTypeReader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\BuilderContextTypeIndex;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\OutputShapeClassifier;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\VariantOrderer;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generates typed IN/OUT payload classes and an invocation holder for each FHIR OperationDefinition.
 *
 * ## What is emitted
 *
 * Per operation, up to three top-level classes plus one nested class per `part[]` group:
 *
 * - `{Stem}Input`  — the IN parameters. Every operation has one; the request body is always a
 *                    `Parameters` resource regardless of the response shape.
 * - `{Stem}Output` — the OUT parameters, **only for the Parameters output shape**. The other three
 *                    shapes have no Output class by design: a bare-resource response *is* the
 *                    resource, so `FhirOperation::$outputClass` points at the model class itself.
 * - `{Stem}Operation` — the holder, carrying invocation metadata and the output-shape discriminator.
 *
 * ## Why these classes carry no `#[FhirProperty]`
 *
 * Payload classes are ergonomic DTOs, not FHIR objects. Tagging them the way models are tagged would
 * put them on `FHIRResourceJsonNormalizer`'s path, which would walk their properties and emit flat
 * keys instead of a parameter array. They carry `#[FhirOperationParameter]` per property and
 * `#[FhirOperationPayload]` on the class, both metadata-only, and reach the wire exclusively through
 * `OperationParameterMapper`.
 *
 * ## Three things that look arbitrary and are not
 *
 * 1. **Variant order is subtype-first**, never alphabetical — `resolveChoiceVariant` matches by
 *    `instanceof` in declaration order, so a supertype listed first steals its subtype's values and
 *    emits the wrong `value[x]` key, silently. Delegated to {@see VariantOrderer}.
 * 2. **Parameter order follows the definition.** `Parameters.parameter` is an ordered wire list, and
 *    definition order is the only ordering with an external authority behind it. Do not sort.
 * 3. **The wire name and the PHP name are both emitted.** Neither is derivable from the other: the
 *    corpus contains `_count`, `use`, `check-system-version` and a published typo that must survive
 *    verbatim.
 *
 * @author Ardenexal
 */
final class FHIROperationGenerator implements GeneratorInterface
{
    /**
     * The one type code with no StructureDefinition at all.
     *
     * `Any` is a wildcard used in OperationDefinition parameter types ("any resource type") — R4's
     * two `$apply` operations declare `return:Any`. Every *other* abstract resource is discovered
     * from `StructureDefinition.abstract` rather than listed here: R5 declares four
     * (`Resource`, `DomainResource`, `CanonicalResource`, `MetadataResource`) and a hand-maintained
     * list of them went stale immediately — `CanonicalResource` produced an unresolvable
     * `CanonicalResourceResource` in R5 output.
     */
    private const string WILDCARD_RESOURCE_CODE = 'Any';

    public function __construct(
        private readonly OperationClassNamer $namer = new OperationClassNamer(),
        private readonly AllowedTypeReader $allowedTypes = new AllowedTypeReader(),
        private readonly VariantOrderer $variantOrderer = new VariantOrderer(),
        private readonly OutputShapeClassifier $shapes = new OutputShapeClassifier(),
    ) {
    }

    /**
     * `kind = 'query'` is excluded by design — it is a named search, not an operation, and its
     * parameters are search parameters rather than a `Parameters` body. One R5 definition.
     *
     * Note that nothing in the pipeline currently dispatches on this method: the generator commands
     * call each generator explicitly. It is implemented honestly so that a future registry gets the
     * right answer, but the live exclusion happens at the call site too.
     */
    public function canGenerate(array $definition): bool
    {
        return ($definition['resourceType'] ?? null) === 'OperationDefinition'
            && ($definition['kind'] ?? null) !== 'query';
    }

    public function getPriority(): int
    {
        return 50;
    }

    /**
     * Generate the holder class, registering payload and nested classes through the context.
     *
     * Returns the holder as the `ClassLike` because it is the entry point a caller names; the
     * Input/Output classes and every nested `part[]` class go through
     * {@see BuilderContextInterface::addType()}, the same side-channel `FHIRModelGenerator` uses to
     * emit backbone elements.
     *
     * @throws GenerationException
     */
    public function generate(array $definition, string $version, BuilderContextInterface $context): ClassType
    {
        if (!$this->canGenerate($definition)) {
            throw GenerationException::operationNamingFailed(sprintf('Not a generatable OperationDefinition: %s', is_string($definition['url'] ?? null) ? $definition['url'] : '(no url)'));
        }

        $stem      = $this->namer->classStem($definition);
        $url       = is_string($definition['url'] ?? null) ? $definition['url'] : '';
        // Namespace nests per operation, matching the file layout — backbone elements set the
        // precedent (`Models\R4\Resource\Patient\PatientContact`). PSR-4 requires the two to
        // agree, and one flat directory would hold ~350 files across 154 operations.
        $namespace = new PhpNamespace($this->operationNamespace($version) . '\\' . $stem);
        $shape     = $this->shapes->classify($definition, new BuilderContextTypeIndex($context));

        $inputClass = $this->buildPayload($definition, $stem, 'in', $version, $context, $namespace);

        $outputClass = null;

        if ($shape['shape'] === OutputShapeClassifier::SHAPE_PARAMETERS) {
            $outputClass = $this->buildPayload($definition, $stem, 'out', $version, $context, $namespace);
        } elseif ($shape['outputType'] !== null) {
            // Bare shapes: the response IS the resource, so the holder points at the model class.
            $outputClass = $this->resourceFqcn($shape['outputType'], $version, $context);
        }

        return $this->buildHolder($definition, $stem, $version, $namespace, $shape, $inputClass, $outputClass, $url);
    }

    /**
     * Build one direction's payload class, recursing into `part[]` groups.
     *
     * @param array<string, mixed> $definition
     *
     * @return string|null FQCN of the generated class, or null when the direction has no parameters
     *
     * @throws GenerationException
     */
    private function buildPayload(
        array $definition,
        string $stem,
        string $use,
        string $version,
        BuilderContextInterface $context,
        PhpNamespace $namespace,
    ): ?string {
        $parameters = $this->parametersFor($definition, $use);

        if ($parameters === []) {
            return null;
        }

        $className = $stem . ucfirst($use === 'in' ? 'input' : 'output');
        $url       = is_string($definition['url'] ?? null) ? $definition['url'] : '';

        $class = $this->buildParameterClass(
            $className,
            $parameters,
            $use,
            $stem,
            [],
            $url,
            $version,
            $context,
            $namespace,
        );

        // Keyed by url + direction: the same operation contributes two classes, and a url-only key
        // would silently overwrite one with the other.
        $context->addType($url . '#' . $use, $namespace->getName(), $class);

        return $namespace->getName() . '\\' . $className;
    }

    /**
     * Build a class from a parameter list, emitting nested `part[]` classes as it goes.
     *
     * @param list<array<string, mixed>> $parameters
     * @param list<string>               $path
     *
     * @throws GenerationException
     */
    private function buildParameterClass(
        string $className,
        array $parameters,
        string $use,
        string $stem,
        array $path,
        string $url,
        string $version,
        BuilderContextInterface $context,
        PhpNamespace $namespace,
    ): ClassType {
        $class = new ClassType($className, $namespace);
        $class->setFinal();
        $class->addAttribute(FhirOperationPayload::class, [
            'operationUrl' => $url,
            'use'          => $use,
            'version'      => $version,
            'operation'    => $stem,
            'path'         => implode('.', $path),
        ]);

        $constructor = $class->addMethod('__construct');
        $phpNames    = [];

        foreach ($parameters as $parameter) {
            $wireName = is_string($parameter['name'] ?? null) ? $parameter['name'] : '';
            $phpName  = $this->namer->propertyName($wireName);

            $phpNames[$wireName] = $phpName;

            $partClass = null;
            $parts     = $parameter['part'] ?? [];

            if (is_array($parts) && $parts !== []) {
                $nestedPath = [...$path, $wireName];
                $nestedName = $stem . $this->namer->partClassName($use, $nestedPath);

                $nested = $this->buildParameterClass(
                    $nestedName,
                    array_values(array_filter($parts, static fn (mixed $p): bool => is_array($p))),
                    $use,
                    $stem,
                    $nestedPath,
                    $url,
                    $version,
                    $context,
                    $namespace,
                );

                $context->addType($url . '#' . $use . '.' . implode('.', $nestedPath), $namespace->getName(), $nested);

                $partClass = $namespace->getName() . '\\' . $nestedName;
            }

            $this->addParameterProperty($constructor, $parameter, $phpName, $partClass, $version, $context);
        }

        // Fatal rather than deduplicated: silently dropping one of two colliding parameters produces
        // a class that looks complete and cannot represent a legal request.
        $this->namer->assertNoCollisions(
            $phpNames,
            sprintf('%s (%s parameters)', $className, $use),
        );

        return $class;
    }

    /**
     * Add one promoted constructor property carrying its `#[FhirOperationParameter]`.
     *
     * @param array<string, mixed> $parameter
     *
     * @throws GenerationException
     */
    private function addParameterProperty(
        Method $constructor,
        array $parameter,
        string $phpName,
        ?string $partClass,
        string $version,
        BuilderContextInterface $context,
    ): void {
        $wireName   = is_string($parameter['name'] ?? null) ? $parameter['name'] : '';
        $max        = (string) ($parameter['max'] ?? '1');
        $isArray    = $max === '*';
        $fhirType   = is_string($parameter['type'] ?? null) ? $parameter['type'] : null;
        $variants   = $this->buildVariants($parameter, $version, $context);

        // The item type is computed once and used twice: as the declared PHP type for a scalar
        // parameter, and inside `list<…>` for a repeating one. PHPStan level 8 rejects a bare
        // `array` without a value type, so a `max: '*'` parameter needs the docblock to pass.
        $itemType = $this->phpTypeFor($fhirType, $partClass, $variants !== [], $version, $context);

        $property = $constructor->addPromotedParameter($phpName, $isArray ? [] : null)
            ->setReadOnly()
            ->setType($isArray ? 'array' : $itemType)
            ->setNullable(!$isArray);

        if ($isArray) {
            // Fully qualified with a leading backslash. Nette resolves `setType()` against the
            // namespace and adds a `use`, but a docblock is raw text — an unqualified FQCN there is
            // read as *relative* to the current namespace, producing
            // `…\Operation\CodeSystemLookup\Ardenexal\FHIRTools\…` and an unresolvable type.
            $docType = str_contains($itemType, '\\') ? '\\' . ltrim($itemType, '\\') : $itemType;

            $constructor->addComment(sprintf('@param list<%s> $%s', $docType, $phpName));
        }

        $arguments = [
            'name'    => $wireName,
            'phpName' => $phpName,
            'use'     => is_string($parameter['use'] ?? null) ? $parameter['use'] : 'in',
            'min'     => (int) ($parameter['min'] ?? 0),
            'max'     => $max,
        ];

        if ($fhirType !== null) {
            $arguments['type'] = $fhirType;
        }

        if ($partClass !== null) {
            $arguments['partClass'] = new Literal('\\' . ltrim($partClass, '\\') . '::class');
        }

        if ($variants !== []) {
            $arguments['variants'] = $variants;
        }

        if (is_string($parameter['searchType'] ?? null)) {
            $arguments['searchType'] = $parameter['searchType'];
        }

        if (is_string($parameter['documentation'] ?? null)) {
            $arguments['documentation'] = $parameter['documentation'];
        }

        $scope = $parameter['scope'] ?? null;

        if (is_array($scope) && $scope !== []) {
            $arguments['scope'] = array_values(array_filter($scope, 'is_string'));
        }

        $property->addAttribute(
            FhirOperationParameter::class,
            $arguments,
        );
    }

    /**
     * Build the ordered `value[x]` variant list for a polymorphic parameter.
     *
     * Ordering is subtype-first (see {@see VariantOrderer}); the alphabetical order
     * {@see AllowedTypeReader} returns exists only so versions compare equal and must never reach
     * the emitted list.
     *
     * @param array<string, mixed> $parameter
     *
     * @return list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string, isBuiltin: bool}>
     *
     * @throws GenerationException
     */
    private function buildVariants(array $parameter, string $version, BuilderContextInterface $context): array
    {
        $types = $this->allowedTypes->read($parameter);

        if ($types === []) {
            $fhirType = is_string($parameter['type'] ?? null) ? $parameter['type'] : null;

            // A polymorphic parameter with no allowed-type source is a defect or a non-core package:
            // measured zero occurrences across all three core packages (M01 note N5). Emitting an
            // untyped property would silently accept anything and serialize unpredictably.
            if ($fhirType === 'Element' || $fhirType === '*') {
                throw GenerationException::operationNamingFailed(sprintf('Parameter "%s" is polymorphic (type "%s") but declares no allowed types, so no value[x] variants can be emitted.', is_string($parameter['name'] ?? null) ? $parameter['name'] : '(unnamed)', $fhirType));
            }

            return [];
        }

        $variants = [];

        foreach ($this->variantOrderer->order($types, $context) as $code) {
            $variants[] = [
                'fhirType'     => $code,
                'propertyKind' => $this->propertyKindFor($code, $context),
                'phpType'      => $this->variantPhpType($code, $version, $context),
                'jsonKey'      => AllowedTypeReader::jsonKeyFor($code),
                'isBuiltin'    => $this->isBuiltin($code),
            ];
        }

        return $variants;
    }

    /**
     * @param array<string, mixed>                                                            $definition
     * @param array{shape: string, outputType: string|null, outputParameterName: string|null} $shape
     *
     * @throws GenerationException
     */
    private function buildHolder(
        array $definition,
        string $stem,
        string $version,
        PhpNamespace $namespace,
        array $shape,
        ?string $inputClass,
        ?string $outputClass,
        string $url,
    ): ClassType {
        $class = new ClassType($stem . 'Operation', $namespace);
        $class->setFinal();

        $resources = array_values(array_filter(
            is_array($definition['resource'] ?? null) ? $definition['resource'] : [],
            'is_string',
        ));

        $arguments = [
            'code'        => is_string($definition['code'] ?? null) ? $definition['code'] : '',
            'url'         => $url,
            'version'     => $version,
            'inputClass'  => $inputClass !== null
                ? new Literal('\\' . ltrim($inputClass, '\\') . '::class')
                : '',
            'outputShape' => new Literal(sprintf(
                '\\%s::%s',
                OperationOutputShape::class,
                $this->shapeCaseName($shape['shape']),
            )),
            'outputClass' => $outputClass !== null
                ? new Literal('\\' . ltrim($outputClass, '\\') . '::class')
                : null,
            'resource'    => $resources,
            'instance'    => (bool) ($definition['instance'] ?? false),
            'type'        => (bool) ($definition['type'] ?? false),
            'system'      => (bool) ($definition['system'] ?? false),
        ];

        if ($shape['outputParameterName'] !== null) {
            $arguments['outputParameterName'] = $shape['outputParameterName'];
        }

        $class->addAttribute(FhirOperation::class, $arguments);

        return $class;
    }

    private function shapeCaseName(string $shape): string
    {
        return match ($shape) {
            OutputShapeClassifier::SHAPE_PARAMETERS          => 'Parameters',
            OutputShapeClassifier::SHAPE_BARE_RESOURCE       => 'BareResource',
            OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE => 'NamedBareResource',
            default                                          => 'NoOutput',
        };
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return list<array<string, mixed>>
     */
    private function parametersFor(array $definition, string $use): array
    {
        $parameters = $definition['parameter'] ?? [];

        if (!is_array($parameters)) {
            return [];
        }

        return array_values(array_filter(
            $parameters,
            static fn (mixed $p): bool => is_array($p) && ($p['use'] ?? null) === $use,
        ));
    }

    /**
     * The PHP type for a parameter property.
     *
     * Primitives are bare PHP scalars because payload classes are ergonomic DTOs; the mapper wraps
     * them into the model's primitive classes on the way out. Polymorphic parameters keep the
     * wrapper — a bare string cannot say which `value[x]` variant was meant.
     */
    private function phpTypeFor(
        ?string $fhirType,
        ?string $partClass,
        bool $isPolymorphic,
        string $version,
        BuilderContextInterface $context,
    ): string {
        if ($partClass !== null) {
            return $partClass;
        }

        if ($isPolymorphic || $fhirType === null) {
            return 'mixed';
        }

        $scalar = $this->scalarFor($fhirType);

        if ($scalar !== null) {
            return $scalar;
        }

        // Resource-typed parameters must land in Resource\, not DataType\. `MeasureReport`,
        // `ValueSet` and `Bundle` all appear as parameter types, and routing everything non-scalar
        // to DataType\ emitted `DataType\MeasureReport`, which does not exist. The kind comes from
        // the type's own StructureDefinition — the same seam the output-shape classifier uses, and
        // for the same reason: capitalisation does not distinguish a resource from a data type.
        return $this->isResourceCode($fhirType, $context)
            ? $this->resourceFqcn($fhirType, $version, $context)
            : $this->modelFqcn($fhirType, $version);
    }

    private function isResourceCode(string $fhirType, BuilderContextInterface $context): bool
    {
        return $fhirType                                                  === self::WILDCARD_RESOURCE_CODE
            || (new BuilderContextTypeIndex($context))->kindOf($fhirType) === 'resource';
    }

    /**
     * Abstract resources and the `Any` wildcard have no concrete generated class.
     *
     * `AbstractResource` is the honest target: it is what every generated resource extends, so the
     * mapper's `$body instanceof $expectedClass` check still means something.
     */
    private function hasNoConcreteClass(string $fhirType, BuilderContextInterface $context): bool
    {
        return $fhirType === self::WILDCARD_RESOURCE_CODE
            || (new BuilderContextTypeIndex($context))->isAbstract($fhirType);
    }

    /**
     * FHIR primitive type code to the bare PHP scalar the DTO declares.
     *
     * `decimal` is a **string**, not a float: FHIR requires precision to be preserved and a float
     * cannot round-trip `1.50`.
     */
    private function scalarFor(string $fhirType): ?string
    {
        return match ($fhirType) {
            'boolean'                                     => 'bool',
            'integer', 'positiveInt', 'unsignedInt',
            'integer64'                                   => 'int',
            'string', 'code', 'uri', 'url', 'canonical',
            'oid', 'uuid', 'id', 'markdown', 'base64Binary',
            'date', 'dateTime', 'instant', 'time', 'decimal' => 'string',
            default                                          => null,
        };
    }

    /**
     * FHIR types the models back with a PHP scalar rather than a wrapper class.
     *
     * Exactly `boolean`, `integer` and `decimal` — the same three `FHIRModelGenerator` treats this
     * way (search: `resolvePropertyKindFromCode`). `positiveInt`, `unsignedInt` and `integer64` are
     * NOT here despite being integral: the models give them real wrapper classes, and a variant
     * claiming otherwise would name a `phpType` that does not match the object at runtime.
     */
    private function isBuiltin(string $fhirType): bool
    {
        return in_array($fhirType, ['boolean', 'integer', 'decimal'], true);
    }

    /**
     * The `propertyKind` label for a variant, matching `FHIRModelGenerator`'s convention exactly.
     *
     * Not cosmetic and not free to diverge: `AbstractFHIRNormalizer::castNumericScalarForJson()`
     * tests `propertyKind === 'scalar'` to decide whether to cast a numeric string back to a number
     * on the way out. Labelling `decimal` as `primitive` here would leave it quoted in JSON while
     * the identical variant on a generated model emits it unquoted — two generators, one wire
     * convention, silently disagreeing. That is the M9 shape, so it is asserted rather than assumed.
     */
    private function propertyKindFor(string $fhirType, BuilderContextInterface $context): string
    {
        if ($this->isBuiltin($fhirType)) {
            return 'scalar';
        }

        return match ((new BuilderContextTypeIndex($context))->kindOf($fhirType)) {
            'primitive-type' => 'primitive',
            'resource'       => 'resource',
            default          => 'complex',
        };
    }

    private function variantPhpType(string $fhirType, string $version, BuilderContextInterface $context): string
    {
        $kind = (new BuilderContextTypeIndex($context))->kindOf($fhirType);

        if ($kind === 'primitive-type') {
            // Only the three scalar-backed types get a bare PHP type; everything else names its
            // wrapper class, because that is what `resolveChoiceVariant` will `instanceof` against.
            return $this->isBuiltin($fhirType)
                ? ($this->scalarFor($fhirType) ?? 'string')
                : $this->primitiveFqcn($fhirType, $version);
        }

        // Same resource-vs-datatype split as phpTypeFor(): a variant typed `Bundle` belongs in
        // Resource\, and emitting DataType\Bundle would produce an unresolvable phpType in the
        // attribute — which the normalizer would then fail to match at runtime.
        return $this->isResourceCode($fhirType, $context)
            ? $this->resourceFqcn($fhirType, $version, $context)
            : $this->modelFqcn($fhirType, $version);
    }

    /**
     * The base operation namespace for a version. Each operation nests one level below it.
     */
    private function operationNamespace(string $version): string
    {
        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Operation', $version);
    }

    /**
     * FQCN literals are correct here: this is generation time, and the emitted string is the
     * base-spec default that a runtime profile later overrides through the type resolver. M01's
     * note N18 forbids building these at *runtime*, which is a different thing.
     *
     * ## Known limitation: constraint-derived complex types would resolve to the wrong namespace
     *
     * This always emits `DataType\{TypeCode}`. A constraint-derived complex type has no class there
     * — `buildProfiles` writes it to `Profile\{Name}Profile` instead (e.g. `SimpleQuantity` becomes
     * `Profile\SimpleQuantityProfile`, not `DataType\SimpleQuantity`). So a parameter typed with one
     * would emit an unresolvable `phpType`, and PHPStan on `Models/` would fail after the regen.
     *
     * Deliberately not fixed, because the path is unreachable in every shipped package and a
     * speculative fix could not be exercised. Measured against the real package cache: of the 40
     * distinct type codes named by any operation parameter `type` or allowed-type variant across
     * R4/R4B/R5, all 40 resolve and **none** is constraint-derived. Fixing it properly needs a
     * type-code -> profile-class mapping, and `FHIRProfileGenerator` names profiles from the
     * StructureDefinition's `name` rather than from the type code, so that mapping is a context read
     * with its own failure modes — worth writing when something actually needs it, with a test that
     * can fail.
     *
     * The related *ordering* half of this problem IS fixed: see `VariantOrderer::depthOf()`, which
     * now counts constraint derivations because they do produce a PHP subclass.
     */
    private function modelFqcn(string $fhirType, string $version): string
    {
        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\DataType\%s', $version, $fhirType);
    }

    private function primitiveFqcn(string $fhirType, string $version): string
    {
        return sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Primitive\%sPrimitive',
            $version,
            ucfirst($fhirType),
        );
    }

    private function resourceFqcn(string $fhirType, string $version, BuilderContextInterface $context): string
    {
        if ($this->hasNoConcreteClass($fhirType, $context)) {
            return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\AbstractResource', $version);
        }

        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\%sResource', $version, $fhirType);
    }
}
