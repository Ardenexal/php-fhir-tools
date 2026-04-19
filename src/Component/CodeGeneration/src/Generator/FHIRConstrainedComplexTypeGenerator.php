<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

use function Symfony\Component\String\u;

/**
 * Generates typed PHP profile classes for constrained FHIR complex types.
 *
 * A constrained complex type profile is a StructureDefinition with:
 *   - type: any complex type (e.g. "Identifier", "Address", "Dosage")
 *   - derivation: "constraint"
 *   - kind: "complex-type"
 *   - one or more elements with fixed[x] or pattern[x] values
 *
 * Unlike the thin marker classes produced by FHIRProfileGenerator, this generator
 * extracts constraint information and bakes fixed values directly into the constructor,
 * so callers only pass the variable (unconstrained) properties.
 *
 * It also emits #[FHIRSliceDiscriminator] attributes on the generated class, one per
 * extracted discriminator, enabling the serialization layer to auto-resolve the correct
 * profile class when deserializing a list of the base type (e.g. Patient.identifier[]).
 *
 * Example output for AU IHI identifier profile:
 * <pre>
 * #[FHIRProfile(profileUrl: '...', baseType: 'Identifier', fhirVersion: 'R4')]
 * #[FHIRSliceDiscriminator(type: 'value', path: 'system', value: 'http://...ihi/1.0')]
 * class AUIHIProfile extends Identifier
 * {
 *     public const string PROFILE_URL = 'http://...';
 *     public const string FIXED_SYSTEM = 'http://...ihi/1.0';
 *
 *     public function __construct(
 *         StringPrimitive|string|null $value = null,
 *         ?IdentifierUseType $use = null,
 *         ?Period $period = null,
 *         ?Reference $assigner = null,
 *         ?string $id = null,
 *         array $extension = [],
 *     ) {
 *         parent::__construct(
 *             id: $id,
 *             extension: $extension,
 *             use: $use,
 *             type: new CodeableConcept(...),
 *             system: new UriPrimitive(self::FIXED_SYSTEM),
 *             value: $value,
 *             period: $period,
 *             assigner: $assigner,
 *         );
 *     }
 * }
 * </pre>
 *
 * @phpstan-type FixedConstraint array{constraintType: 'fixed'|'pattern', fhirKey: string, value: mixed}
 * @phpstan-type ExtractedConstraints array<string, FixedConstraint>
 *
 * @author Ardenexal
 */
class FHIRConstrainedComplexTypeGenerator
{
    /**
     * FHIR primitive type codes that map to the Primitive/ namespace wrapper classes.
     *
     * @var list<string>
     */
    private const array PRIMITIVE_TYPES = [
        'string', 'integer64', 'uri', 'url', 'canonical', 'base64Binary', 'instant',
        'date', 'dateTime', 'time', 'code', 'oid', 'id', 'markdown', 'unsignedInt',
        'positiveInt', 'uuid', 'xhtml', 'decimal',
    ];

    /**
     * FHIR type codes that map to known PHP scalar types.
     *
     * @var array<string, string>
     */
    private const array SCALAR_TYPES = [
        'boolean'                                => 'bool',
        'integer'                                => 'int',
        'decimal'                                => 'string',
        'http://hl7.org/fhirpath/System.Boolean' => 'bool',
        'http://hl7.org/fhirpath/System.Integer' => 'int',
        'http://hl7.org/fhirpath/System.Decimal' => 'string',
        'http://hl7.org/fhirpath/System.String'  => 'string',
    ];

    /**
     * Determine whether a StructureDefinition has any fixed[x] or pattern[x] constraints
     * on its top-level properties that would benefit from constructor baking.
     *
     * Used by the command to decide whether to use this generator or FHIRProfileGenerator.
     *
     * @param array<string, mixed> $structureDefinition
     */
    public static function hasConstrainedElements(array $structureDefinition): bool
    {
        $baseType = $structureDefinition['type'] ?? '';
        $elements = $structureDefinition['snapshot']['element'] ?? [];

        if ($baseType === '' || !is_array($elements)) {
            return false;
        }

        $prefix = $baseType . '.';

        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            $path = (string) ($element['path'] ?? '');

            if (!str_starts_with($path, $prefix)) {
                continue;
            }

            $remainder = substr($path, strlen($prefix));

            // Only consider direct (one-level) properties
            if ($remainder === '' || str_contains($remainder, '.')) {
                continue;
            }

            foreach (array_keys($element) as $key) {
                if (str_starts_with($key, 'fixed') || str_starts_with($key, 'pattern')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Generate a typed constrained complex type profile class from a FHIR StructureDefinition.
     *
     * @param array<string, mixed> $structureDefinition StructureDefinition with derivation=constraint, kind=complex-type
     * @param string               $version             FHIR version (e.g. 'R4')
     * @param BuilderContext       $context             Builder context for type resolution
     * @param PhpNamespace         $namespace           Target namespace for the generated class
     * @param ErrorCollector|null  $errorCollector      Optional collector for warnings
     *
     * @return ClassType The generated PHP class
     */
    public function generate(
        array $structureDefinition,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        ?ErrorCollector $errorCollector = null,
    ): ClassType {
        $url               = (string) ($structureDefinition['url']            ?? '');
        $name              = (string) ($structureDefinition['name']           ?? 'Unknown');
        $baseType          = (string) ($structureDefinition['type']           ?? '');
        $baseDefinitionUrl = (string) ($structureDefinition['baseDefinition'] ?? '');
        $kind              = (string) ($structureDefinition['kind']           ?? 'complex-type');
        $elements          = $structureDefinition['snapshot']['element']      ?? [];

        $className = $this->resolveProfileClassName($name, $kind);
        $class     = new ClassType($className, $namespace);

        $class->addAttribute(FHIRProfile::class, [
            'profileUrl'  => $url,
            'baseType'    => $baseType,
            'fhirVersion' => $version,
        ]);

        if (isset($structureDefinition['publisher'])) {
            $class->addComment('@author ' . $structureDefinition['publisher']);
        }
        $class->addComment('@see ' . $url);
        if (!empty($structureDefinition['description'])) {
            $class->addComment('@description ' . $structureDefinition['description']);
        }

        // Resolve parent class — may be the base FHIR type or a previously generated IG profile
        $parentFqcn = $this->resolveParentFqcn($baseDefinitionUrl, $version, $context, $errorCollector);
        $class->setExtends('\\' . $parentFqcn);
        $namespace->addUse($parentFqcn);

        $class->addConstant('PROFILE_URL', $url)
            ->setType('string')
            ->addComment('Canonical URL of this profile\'s StructureDefinition.');

        // Extract fixed[x] / pattern[x] constraints from snapshot elements
        /** @var ExtractedConstraints $fixedConstraints */
        $fixedConstraints = $this->extractFixedConstraints($elements, $baseType);

        // Emit #[FHIRSliceDiscriminator] attributes for each extracted constraint
        foreach ($this->buildDiscriminatorAttributes($fixedConstraints) as $discriminatorArgs) {
            $class->addAttribute(FHIRSliceDiscriminator::class, $discriminatorArgs);
        }

        if (empty($fixedConstraints)) {
            // No fixed values found — fall back to thin marker class (only PROFILE_URL constant)
            return $class;
        }

        // Add string constants for simple (scalar) fixed values (e.g. FIXED_SYSTEM)
        $this->addFixedConstants($class, $fixedConstraints);

        // Build a constructor that bakes in fixed values and exposes variable params
        $this->buildConstructor($class, $parentFqcn, $elements, $baseType, $fixedConstraints, $namespace, $version, $context, $errorCollector);

        return $class;
    }

    /**
     * Derive the PHP class name for the profile (with "Profile" suffix unless already present).
     */
    private function resolveProfileClassName(string $name, string $kind): string
    {
        $base = ClassNameResolver::resolveClassName('', $name);

        return str_ends_with($base, 'Profile') ? $base : $base . 'Profile';
    }

    /**
     * Resolve the FQCN of the parent class.
     *
     * @see FHIRProfileGenerator::resolveParentFqcn() — same lookup logic
     */
    private function resolveParentFqcn(
        string $baseDefinitionUrl,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): string {
        $info = $context->getType($baseDefinitionUrl);
        if ($info !== null) {
            return ltrim($info->fqcn, '\\');
        }

        $resourceInfo = $context->getResource($baseDefinitionUrl);
        if ($resourceInfo !== null) {
            return ltrim($resourceInfo->fqcn, '\\');
        }

        $segment      = (string) u($baseDefinitionUrl)->afterLast('/');
        $baseNs       = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
        $className    = u($segment)->pascal()->toString();
        $fallbackFqcn = "{$baseNs}\\DataType\\{$className}";

        $errorCollector?->addWarning(
            "Could not resolve baseDefinition URL '{$baseDefinitionUrl}' — using fallback FQCN "
            . "'{$fallbackFqcn}'. Ensure the package providing this type is included.",
            $baseDefinitionUrl,
        );

        return $fallbackFqcn;
    }

    /**
     * Scan snapshot elements for fixed[x] / pattern[x] values on direct (one-level) properties.
     *
     * @param array<int, mixed> $elements
     *
     * @return ExtractedConstraints  property name → constraint metadata
     */
    private function extractFixedConstraints(array $elements, string $baseType): array
    {
        $constraints = [];
        $prefix      = $baseType . '.';

        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            $path = (string) ($element['path'] ?? '');

            if (!str_starts_with($path, $prefix)) {
                continue;
            }

            $propertyName = substr($path, strlen($prefix));

            // Only direct properties (no dots in the remainder)
            if ($propertyName === '' || str_contains($propertyName, '.')) {
                continue;
            }

            foreach ($element as $key => $value) {
                if (str_starts_with($key, 'fixed') && strlen($key) > 5) {
                    $constraints[$propertyName] = [
                        'constraintType' => 'fixed',
                        'fhirKey'        => $key,
                        'value'          => $value,
                    ];
                    break;
                }

                if (str_starts_with($key, 'pattern') && strlen($key) > 7) {
                    $constraints[$propertyName] = [
                        'constraintType' => 'pattern',
                        'fhirKey'        => $key,
                        'value'          => $value,
                    ];
                    break;
                }
            }
        }

        return $constraints;
    }

    /**
     * Build the arguments for #[FHIRSliceDiscriminator] attribute(s) from extracted constraints.
     *
     * Returns one attribute argument array per discriminator. Value discriminators use
     * 'value' type; pattern discriminators use 'pattern' type.
     *
     * @param ExtractedConstraints $fixedConstraints
     *
     * @return list<array{type: string, path: string, value: mixed}>
     */
    private function buildDiscriminatorAttributes(array $fixedConstraints): array
    {
        $attrs = [];

        foreach ($fixedConstraints as $propertyName => $constraint) {
            $discriminatorType = $constraint['constraintType'] === 'fixed' ? 'value' : 'pattern';
            $value             = $constraint['value'];

            // For complex value discriminators (CodeableConcept, etc.), extract a
            // normalised representation suitable for pattern matching.
            if (is_array($value)) {
                $value = $this->normalisePatternValue($value);
            }

            $attrs[] = [
                'type'  => $discriminatorType,
                'path'  => $propertyName,
                'value' => $value,
            ];
        }

        return $attrs;
    }

    /**
     * Normalise a complex FHIR pattern value (e.g. patternCodeableConcept) to a
     * plain PHP array suitable for storage in a PHP attribute and discriminator matching.
     *
     * Only keeps leaf-level scalar values; strips FHIR metadata keys that are not
     * relevant for discrimination (e.g. 'display' on a coding is informational only).
     *
     * @param array<string, mixed> $value
     *
     * @return array<string, mixed>
     */
    private function normalisePatternValue(array $value): array
    {
        // For CodeableConcept patterns: keep coding system + code; drop display and text
        if (isset($value['coding']) && is_array($value['coding'])) {
            $normCodings = [];

            foreach ($value['coding'] as $coding) {
                if (!is_array($coding)) {
                    continue;
                }

                $normCoding = [];
                if (isset($coding['system'])) {
                    $normCoding['system'] = $coding['system'];
                }
                if (isset($coding['code'])) {
                    $normCoding['code'] = $coding['code'];
                }
                if ($normCoding !== []) {
                    $normCodings[] = $normCoding;
                }
            }

            return ['coding' => $normCodings];
        }

        return $value;
    }

    /**
     * Add string constants for simple (scalar-valued) fixed constraints.
     *
     * Only scalar fixed values (strings, URIs) get constants. Complex values
     * (e.g. patternCodeableConcept) are too nested to represent as a single constant.
     *
     * @param ExtractedConstraints $fixedConstraints
     */
    private function addFixedConstants(ClassType $class, array $fixedConstraints): void
    {
        foreach ($fixedConstraints as $propertyName => $constraint) {
            if (!is_string($constraint['value'])) {
                continue;
            }

            $constantName = 'FIXED_' . strtoupper((string) u($propertyName)->snake());
            $class->addConstant($constantName, $constraint['value'])
                ->setType('string')
                ->addComment('Fixed value for ' . $propertyName . ' as required by this profile.');
        }
    }

    /**
     * Build a typed constructor that bakes in fixed values and exposes variable params.
     *
     * Uses PHP reflection on the resolved parent class to discover the constructor
     * parameter order, types, and defaults — then partitions them into fixed (baked-in)
     * and variable (exposed) sets.
     *
     * @param array<int, mixed>    $elements
     * @param ExtractedConstraints $fixedConstraints
     */
    private function buildConstructor(
        ClassType $class,
        string $parentFqcn,
        array $elements,
        string $baseType,
        array $fixedConstraints,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): void {
        // Reflect on the parent class to get its constructor parameter signature
        try {
            /** @var class-string $parentFqcn */
            $parentRefl = new \ReflectionClass($parentFqcn);
            $parentCtor = $parentRefl->getConstructor();
        } catch (\ReflectionException) {
            // If parent can't be loaded, skip constructor generation
            $errorCollector?->addWarning(
                "Could not reflect on parent class '{$parentFqcn}' — skipping constructor generation.",
                $parentFqcn,
            );

            return;
        }

        if ($parentCtor === null) {
            return;
        }

        $constructor = $class->addMethod('__construct');

        // Build element min-cardinality map for determining required params
        $minCardinality = $this->extractMinCardinality($elements, $baseType);

        $callArgs     = [];
        $requiredVars = [];
        $optionalVars = [];

        foreach ($parentCtor->getParameters() as $param) {
            $paramName = $param->getName();

            if (isset($fixedConstraints[$paramName])) {
                // Fixed param: bake the value into the parent::__construct() call
                $callArgs[$paramName] = $this->buildFixedValueExpression(
                    $paramName,
                    $fixedConstraints[$paramName],
                    $namespace,
                    $version,
                    $context,
                    $errorCollector,
                );
                continue;
            }

            // Variable param: expose it as a constructor parameter
            $phpType    = $this->resolveParamPhpType($param);
            $isRequired = isset($minCardinality[$paramName]) && $minCardinality[$paramName] >= 1
                && !$param->isVariadic()
                && $phpType !== 'array';

            $varData = [
                'name'       => $paramName,
                'phpType'    => $phpType,
                'isRequired' => $isRequired,
                'param'      => $param,
            ];

            if ($isRequired) {
                $requiredVars[] = $varData;
            } else {
                $optionalVars[] = $varData;
            }

            $callArgs[$paramName] = '$' . $paramName;
        }

        // Add required params first, then optional — avoids PHP "optional before required" E_DEPRECATED
        foreach (array_merge($requiredVars, $optionalVars) as $varData) {
            $paramName  = $varData['name'];
            $phpType    = $varData['phpType'];
            $isRequired = $varData['isRequired'];
            /** @var \ReflectionParameter $reflParam */
            $reflParam  = $varData['param'];

            $p = $constructor->addParameter($paramName)->setType($phpType);

            if (!$isRequired) {
                $p->setNullable(str_contains($phpType, '|null') === false && !str_starts_with($phpType, '?'));

                if ($phpType === 'array') {
                    $p->setDefaultValue([]);
                } elseif (!str_contains($phpType, 'array')) {
                    // Carry over the parent's default if available, otherwise null
                    try {
                        $p->setDefaultValue($reflParam->isDefaultValueAvailable() ? $reflParam->getDefaultValue() : null);
                    } catch (\ReflectionException) {
                        $p->setDefaultValue(null);
                    }
                }
            }

            // Add used types to namespace
            foreach (explode('|', str_replace('?', '', $phpType)) as $part) {
                $part = trim($part);
                if ($part !== '' && $part !== 'null' && !in_array($part, ['bool', 'int', 'string', 'float', 'array', 'mixed'], true)) {
                    $namespace->addUse(ltrim($part, '\\'));
                }
            }
        }

        // Assemble parent::__construct() call
        $argLines = [];
        foreach ($callArgs as $name => $expr) {
            $argLines[] = "    {$name}: {$expr}";
        }

        $constructor->setBody(
            "parent::__construct(\n" . implode(",\n", $argLines) . ",\n);"
        );
    }

    /**
     * Build the PHP expression for a fixed/pattern value to use in the parent::__construct() call.
     *
     * @param FixedConstraint $constraint
     */
    private function buildFixedValueExpression(
        string $propertyName,
        array $constraint,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): string {
        $fhirKey = $constraint['fhirKey'];
        $value   = $constraint['value'];

        // Derive the FHIR type from the key suffix.
        // e.g. "fixedUri" → suffix "Uri" → FHIR primitive "uri" (lowercase)
        //      "patternCodeableConcept" → suffix "CodeableConcept" → complex type (PascalCase preserved)
        $prefix   = $constraint['constraintType'] === 'fixed' ? 'fixed' : 'pattern';
        $suffix   = substr($fhirKey, strlen($prefix));
        // Primitives use lowercase FHIR type codes; complex types use PascalCase.
        $fhirType = in_array(strtolower($suffix), self::PRIMITIVE_TYPES, true)
            ? strtolower($suffix)
            : $suffix;

        // Simple string fixed values use the FIXED_ constant
        if (is_string($value)) {
            $constantName = 'FIXED_' . strtoupper((string) u($propertyName)->snake());
            $phpClass     = $this->resolvePhpClassForFhirType($fhirType, $version, $context, $errorCollector);

            if ($phpClass !== null) {
                $namespace->addUse(ltrim($phpClass, '\\'));
                $shortClass = (string) u($phpClass)->afterLast('\\');

                return "new {$shortClass}(self::{$constantName})";
            }

            // Scalar (bool/int/string) — just use the constant directly
            return "self::{$constantName}";
        }

        // Complex value (array) — build recursive PHP constructor expression
        if (is_array($value)) {
            return $this->buildComplexValueExpression($fhirType, $value, $namespace, $version, $context, $errorCollector);
        }

        // Fallback: use var_export for unknown scalar types
        return var_export($value, true);
    }

    /**
     * Build a PHP constructor-call expression for a complex FHIR value (e.g. patternCodeableConcept).
     *
     * @param array<string, mixed> $value
     */
    private function buildComplexValueExpression(
        string $fhirType,
        array $value,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): string {
        $phpClass = $this->resolvePhpClassForFhirType($fhirType, $version, $context, $errorCollector);

        if ($phpClass === null) {
            $errorCollector?->addWarning(
                "Cannot generate PHP expression for FHIR type '{$fhirType}' — using null fallback.",
                $fhirType,
            );

            return 'null';
        }

        $namespace->addUse(ltrim($phpClass, '\\'));
        $shortClass = (string) u($phpClass)->afterLast('\\');

        // Special handling for known complex types
        return match ($fhirType) {
            'CodeableConcept' => $this->buildCodeableConceptExpression($value, $namespace, $version, $context, $errorCollector),
            'Coding'          => $this->buildCodingExpression($value, $namespace, $version, $context),
            default           => $this->buildGenericObjectExpression($shortClass, $value, $namespace, $version, $context, $errorCollector),
        };
    }

    /**
     * Build a PHP expression for a CodeableConcept value.
     *
     * @param array<string, mixed> $value
     */
    private function buildCodeableConceptExpression(
        array $value,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector,
    ): string {
        $ccClass = $this->resolvePhpClassForFhirType('CodeableConcept', $version, $context, $errorCollector);
        if ($ccClass === null) {
            return 'null';
        }

        $namespace->addUse(ltrim($ccClass, '\\'));
        $shortClass = (string) u($ccClass)->afterLast('\\');

        $args = [];

        if (isset($value['coding']) && is_array($value['coding'])) {
            $codingExprs = [];

            foreach ($value['coding'] as $coding) {
                if (is_array($coding)) {
                    $codingExprs[] = $this->buildCodingExpression($coding, $namespace, $version, $context);
                }
            }

            if ($codingExprs !== []) {
                $args[] = 'coding: [' . implode(', ', $codingExprs) . ']';
            }
        }

        if (isset($value['text']) && is_string($value['text'])) {
            $strClass = $this->resolvePhpClassForFhirType('string', $version, $context, $errorCollector);
            if ($strClass !== null) {
                $namespace->addUse(ltrim($strClass, '\\'));
                $shortStr = (string) u($strClass)->afterLast('\\');
                $text     = addslashes($value['text']);
                $args[]   = "text: new {$shortStr}('{$text}')";
            }
        }

        return "new {$shortClass}(" . implode(', ', $args) . ')';
    }

    /**
     * Build a PHP expression for a Coding value.
     *
     * @param array<string, mixed> $coding
     */
    private function buildCodingExpression(
        array $coding,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
    ): string {
        $codingClass = $this->resolvePhpClassForFhirType('Coding', $version, $context);
        if ($codingClass === null) {
            return 'null';
        }

        $namespace->addUse(ltrim($codingClass, '\\'));
        $shortClass = (string) u($codingClass)->afterLast('\\');

        $uriClass = $this->resolvePhpClassForFhirType('uri', $version, $context);
        $strClass = $this->resolvePhpClassForFhirType('string', $version, $context);
        $codeClass = $this->resolvePhpClassForFhirType('code', $version, $context);

        $args = [];

        if (isset($coding['system']) && is_string($coding['system']) && $uriClass !== null) {
            $namespace->addUse(ltrim($uriClass, '\\'));
            $shortUri = (string) u($uriClass)->afterLast('\\');
            $system   = addslashes($coding['system']);
            $args[]   = "system: new {$shortUri}('{$system}')";
        }

        if (isset($coding['code']) && is_string($coding['code']) && $codeClass !== null) {
            $namespace->addUse(ltrim($codeClass, '\\'));
            $shortCode = (string) u($codeClass)->afterLast('\\');
            $code      = addslashes($coding['code']);
            $args[]    = "code: new {$shortCode}('{$code}')";
        }

        if (isset($coding['display']) && is_string($coding['display']) && $strClass !== null) {
            $namespace->addUse(ltrim($strClass, '\\'));
            $shortStr = (string) u($strClass)->afterLast('\\');
            $display  = addslashes($coding['display']);
            $args[]   = "display: new {$shortStr}('{$display}')";
        }

        return "new {$shortClass}(" . implode(', ', $args) . ')';
    }

    /**
     * Build a generic PHP object constructor expression from a FHIR value array.
     *
     * For unknown complex types, outputs `new TypeName(key1: val1, key2: val2, ...)` where
     * scalar values are var_export'd. This is a best-effort fallback.
     *
     * @param array<string, mixed> $value
     */
    private function buildGenericObjectExpression(
        string $shortClass,
        array $value,
        PhpNamespace $namespace,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): string {
        $args = [];

        foreach ($value as $key => $val) {
            if (is_scalar($val)) {
                $args[] = $key . ': ' . var_export($val, true);
            }
            // Skip non-scalar nested values in the generic fallback
        }

        return "new {$shortClass}(" . implode(', ', $args) . ')';
    }

    /**
     * Resolve the PHP class FQCN for a given FHIR type code.
     *
     * Returns null for PHP scalar types (bool, int, string) that don't have a wrapper class.
     */
    private function resolvePhpClassForFhirType(
        string $fhirType,
        string $version,
        BuilderContext $context,
        ?ErrorCollector $errorCollector = null,
    ): ?string {
        // PHP scalar types have no wrapper class
        if (isset(self::SCALAR_TYPES[$fhirType])) {
            return null;
        }

        // Normalise to URL for context lookup
        $url = str_starts_with($fhirType, 'http://') || str_starts_with($fhirType, 'https://')
            ? $fhirType
            : 'http://hl7.org/fhir/StructureDefinition/' . $fhirType;

        $info = $context->getType($url);
        if ($info !== null) {
            return $info->fqcn;
        }

        $baseNs = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";

        if (in_array($fhirType, self::PRIMITIVE_TYPES, true)) {
            $className = u($fhirType)->pascal()->toString() . 'Primitive';

            return "\\{$baseNs}\\Primitive\\{$className}";
        }

        // Assume DataType namespace for complex types
        $className = u($fhirType)->pascal()->toString();

        return "\\{$baseNs}\\DataType\\{$className}";
    }

    /**
     * Extract the min cardinality for each direct property from the snapshot elements.
     *
     * Used to determine which variable constructor parameters should be required (non-nullable).
     *
     * @param array<int, mixed> $elements
     *
     * @return array<string, int>
     */
    private function extractMinCardinality(array $elements, string $baseType): array
    {
        $cardinality = [];
        $prefix      = $baseType . '.';

        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            $path = (string) ($element['path'] ?? '');

            if (!str_starts_with($path, $prefix)) {
                continue;
            }

            $propertyName = substr($path, strlen($prefix));

            if ($propertyName === '' || str_contains($propertyName, '.')) {
                continue;
            }

            if (isset($element['min'])) {
                $cardinality[$propertyName] = (int) $element['min'];
            }
        }

        return $cardinality;
    }

    /**
     * Resolve the PHP type string for a reflected constructor parameter.
     *
     * Preserves the parent's exact type (including union types like StringPrimitive|string|null).
     */
    private function resolveParamPhpType(\ReflectionParameter $param): string
    {
        $type = $param->getType();

        if ($type === null) {
            return 'mixed';
        }

        return $this->reflectionTypeToString($type);
    }

    /**
     * Convert a ReflectionType to its PHP type string representation.
     */
    private function reflectionTypeToString(\ReflectionType $type): string
    {
        if ($type instanceof \ReflectionUnionType) {
            $parts = array_map(
                fn (\ReflectionType $t): string => $this->reflectionTypeToString($t),
                $type->getTypes(),
            );

            return implode('|', $parts);
        }

        if ($type instanceof \ReflectionNamedType) {
            $name = $type->getName();

            if ($name === 'null') {
                return 'null';
            }

            if ($type->isBuiltin()) {
                return $name;
            }

            return '\\' . $name;
        }

        if ($type instanceof \ReflectionIntersectionType) {
            $parts = array_map(
                fn (\ReflectionType $t): string => $this->reflectionTypeToString($t),
                $type->getTypes(),
            );

            return implode('&', $parts);
        }

        return 'mixed';
    }
}
