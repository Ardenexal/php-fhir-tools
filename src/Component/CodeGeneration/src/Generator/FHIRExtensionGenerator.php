<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Method;

use function Symfony\Component\String\u;

/**
 * Generates typed PHP classes for named FHIR extensions.
 *
 * A named FHIR extension is a StructureDefinition with:
 *   - type: "Extension"
 *   - derivation: "constraint"
 *
 * The generated class extends the base Extension type and constrains:
 *   - Simple extensions: the value[x] type to a concrete PHP type
 *   - Complex extensions: sub-extension slices to typed properties
 *
 * Simple extension example (patient-birthPlace → PatientBirthPlaceExtension):
 * <pre>
 * #[FHIRExtensionDefinition(url: '...', fhirVersion: 'R4')]
 * class PatientBirthPlaceExtension extends Extension
 * {
 *     public function __construct(
 *         public ?Address $valueAddress = null,
 *         ?string $id = null,
 *         array $extension = [],
 *     ) {
 *         parent::__construct(id: $id, extension: $extension, url: '...', value: $this->valueAddress);
 *     }
 * }
 * </pre>
 *
 * Complex extension example (us-core-race → UsCoreRaceExtension):
 * <pre>
 * #[FHIRExtensionDefinition(url: '...', fhirVersion: 'R4')]
 * class UsCoreRaceExtension extends Extension
 * {
 *     public function __construct(
 *         public array $ombCategory = [],    // Coding[]
 *         public ?StringPrimitive $text = null,
 *         ?string $id = null,
 *     ) {
 *         $subExtensions = [];
 *         foreach ($this->ombCategory as $v) {
 *             $subExtensions[] = new Extension(url: 'ombCategory', value: $v);
 *         }
 *         ...
 *         parent::__construct(id: $id, extension: $subExtensions, url: '...');
 *     }
 * }
 * </pre>
 *
 * @phpstan-type SnapshotElement array{
 *     id?: string,
 *     path: string,
 *     sliceName?: string,
 *     min?: int,
 *     max?: string,
 *     type?: list<array{code: string}>,
 *     short?: string,
 *     definition?: string,
 * }
 *
 * @author Ardenexal
 */
class FHIRExtensionGenerator
{
    /**
     * FHIR primitive type codes that map to PHP wrapper classes in the Primitive/ namespace.
     *
     * @var list<string>
     */
    private const array PRIMITIVE_TYPES = [
        'string', 'integer64', 'uri', 'url', 'canonical', 'base64Binary', 'instant',
        'date', 'dateTime', 'time', 'code', 'oid', 'id', 'markdown', 'unsignedInt',
        'positiveInt', 'uuid', 'xhtml', 'decimal',
    ];

    /**
     * Generate a typed extension class from a FHIR extension StructureDefinition.
     *
     * @param array<string, mixed> $structureDefinition StructureDefinition with type=Extension, derivation=constraint
     * @param string               $version             FHIR version (e.g. 'R4')
     * @param BuilderContext       $context             Builder context for type resolution
     * @param PhpNamespace         $namespace           Target namespace for the generated class
     * @param ErrorCollector|null  $errorCollector      Optional collector for unresolvable-type warnings
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
        $url       = $structureDefinition['url']  ?? '';
        $name      = $structureDefinition['name'] ?? 'UnknownExtension';
        $className = ClassNameResolver::resolveClassName($url, $name) . 'Extension';

        $class = new ClassType($className, $namespace);
        $class->addAttribute(FHIRExtensionDefinition::class, [
            'url'         => $url,
            'fhirVersion' => $version,
        ]);

        if (isset($structureDefinition['publisher'])) {
            $class->addComment('@author ' . $structureDefinition['publisher']);
        }
        $class->addComment('@see ' . $url);
        if (!empty($structureDefinition['description'])) {
            $class->addComment('@description ' . $structureDefinition['description']);
        }

        // Extend the base Extension class for this FHIR version
        $extensionFqcn = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\DataType\\Extension";
        $class->setExtends('\\' . $extensionFqcn);
        $namespace->addUse($extensionFqcn);

        $elements    = $structureDefinition['snapshot']['element'] ?? [];
        $constructor = $class->addMethod('__construct');

        if ($this->isComplexExtension($elements)) {
            $complexInterfaceFqcn = FHIRComplexExtensionInterface::class;
            $namespace->addUse($complexInterfaceFqcn);
            $class->addImplement('\\' . $complexInterfaceFqcn);
            $this->buildComplexConstructor($constructor, $elements, $version, $context, $namespace, $extensionFqcn, $url, $errorCollector);
            $this->buildFromSubExtensionsMethod($class, $elements, $version, $context, $namespace, $url, $errorCollector);
        } else {
            $this->buildSimpleConstructor($constructor, $elements, $version, $context, $namespace, $url, $errorCollector);
        }

        return $class;
    }

    /**
     * Detect whether this extension uses sub-extension slices (complex) vs a value[x] (simple).
     *
     * A complex extension has at least one element with:
     *   - path == "Extension.extension"
     *   - sliceName set (indicating a named slice)
     *   - max != "0"
     *
     * @param array<int, mixed> $elements
     */
    private function isComplexExtension(array $elements): bool
    {
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            if (($element['path'] ?? '') === 'Extension.extension'
                && isset($element['sliceName'])
                && ($element['max'] ?? '0') !== '0'
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build a simple extension constructor: one typed value property + URL baked in.
     *
     * @param array<int, mixed> $elements
     */
    private function buildSimpleConstructor(
        Method $constructor,
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $url,
        ?ErrorCollector $errorCollector = null,
    ): void {
        // Find the value[x] element
        $valueElement = $this->findValueElement($elements);

        if ($valueElement !== null) {
            $types = $valueElement['type'] ?? [];

            if (count($types) === 1) {
                // Single concrete type: generate a named, typed property
                $code      = $types[0]['code'] ?? 'string';
                $phpType   = $this->resolvePhpType($code, $version, $context, $errorCollector);
                $paramName = 'value' . u($code)->pascal()->toString();
                $shortDesc = $valueElement['short'] ?? 'Value of extension';

                if ($phpType !== 'bool' && $phpType !== 'int' && $phpType !== 'string') {
                    $namespace->addUse(ltrim($phpType, '\\'));
                    $shortType = (string) u($phpType)->afterLast('\\');
                } else {
                    $shortType = $phpType;
                }

                $param = $constructor->addPromotedParameter($paramName)
                    ->setPublic()
                    ->setType($phpType)
                    ->setNullable(true)
                    ->setDefaultValue(null);
                $param->addAttribute(FhirProperty::class, [
                    'fhirType'     => $code,
                    'propertyKind' => $this->resolvePropertyKindFromCode($code),
                ]);
                $param->addComment("@var {$phpType}|null {$paramName} {$shortDesc}");

                $constructor->addParameter('id')
                    ->setType('string')
                    ->setNullable(true)
                    ->setDefaultValue(null);
                $constructor->addParameter('extension')
                    ->setType('array')
                    ->setNullable(false)
                    ->setDefaultValue([]);

                $constructor->setBody(
                    "parent::__construct(\n" .
                    "    id: \$id,\n" .
                    "    extension: \$extension,\n" .
                    "    url: '{$url}',\n" .
                    "    value: \$this->{$paramName},\n" .
                    ');',
                );

                return;
            }

            if (count($types) > 1) {
                // Multiple allowed types: expose as a generic value property
                $this->buildMultiTypeValueConstructor($constructor, $types, $version, $context, $namespace, $url, $errorCollector);

                return;
            }
        }

        // No value element (URL-only extension, or all types suppressed): bare constructor
        $constructor->addParameter('id')
            ->setType('string')
            ->setNullable(true)
            ->setDefaultValue(null);
        $constructor->addParameter('extension')
            ->setType('array')
            ->setNullable(false)
            ->setDefaultValue([]);
        $constructor->setBody(
            "parent::__construct(\n" .
            "    id: \$id,\n" .
            "    extension: \$extension,\n" .
            "    url: '{$url}',\n" .
            ');',
        );
    }

    /**
     * Build a constructor for a simple extension with multiple allowed value types.
     *
     * @param list<array{code: string}> $types
     */
    private function buildMultiTypeValueConstructor(
        Method $constructor,
        array $types,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $url,
        ?ErrorCollector $errorCollector = null,
    ): void {
        // Union of all allowed PHP types
        $phpTypes  = [];
        $fhirTypes = [];

        foreach ($types as $t) {
            $code        = $t['code'];
            $phpTypes[]  = $this->resolvePhpType($code, $version, $context, $errorCollector);
            $fhirTypes[] = $code;

            $phpType = end($phpTypes);
            if ($phpType !== 'bool' && $phpType !== 'int' && $phpType !== 'string') {
                $namespace->addUse(ltrim($phpType, '\\'));
            }
        }

        $unionType  = implode('|', array_unique($phpTypes)) . '|null';
        $shortTypes = implode('|', array_map(
            static fn (string $t): string => (string) u($t)->afterLast('\\'),
            array_unique($phpTypes),
        ));

        // Use a regular (non-promoted) parameter to avoid redeclaring the parent Extension::$value
        // property with a narrower type, which PHP rejects as a property type invariance violation.
        $param = $constructor->addParameter('value')
            ->setType($unionType)
            ->setDefaultValue(null);
        $param->addAttribute(FhirProperty::class, [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isChoice'     => true,
        ]);
        $param->addComment("@var {$unionType} value Value of extension");

        $constructor->addParameter('id')
            ->setType('string')
            ->setNullable(true)
            ->setDefaultValue(null);
        $constructor->addParameter('extension')
            ->setType('array')
            ->setNullable(false)
            ->setDefaultValue([]);
        $constructor->setBody(
            "parent::__construct(\n" .
            "    id: \$id,\n" .
            "    extension: \$extension,\n" .
            "    url: '{$url}',\n" .
            "    value: \$value,\n" .
            ');',
        );
    }

    /**
     * Build a complex extension constructor: typed properties for each sub-extension slice.
     *
     * The constructor body assembles the typed sub-extension values into generic Extension
     * objects and passes them to parent::__construct() as the extension array, enabling
     * serialization via the existing Extension-based serializer.
     *
     * @param array<int, mixed> $elements
     */
    private function buildComplexConstructor(
        Method $constructor,
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $extensionFqcn,
        string $url,
        ?ErrorCollector $errorCollector = null,
    ): void {
        $slices    = $this->collectSlices($elements, $version, $context, $namespace, $errorCollector);

        // PHP deprecates optional parameters appearing before required ones (E_DEPRECATED).
        // Sort slices so non-nullable/non-array (required) ones come first.
        usort($slices, static function(array $a, array $b): int {
            $aRequired = $a['isRequired'] && !$a['isArray'];
            $bRequired = $b['isRequired'] && !$b['isArray'];

            return ($bRequired ? 1 : 0) <=> ($aRequired ? 1 : 0);
        });

        $bodyLines = ['$subExtensions = [];'];

        foreach ($slices as $sliceData) {
            $sliceName  = $sliceData['sliceName'];
            $paramName  = $sliceData['paramName'];
            $phpType    = $sliceData['phpType'];
            $sliceCode  = $sliceData['sliceCode'];
            $isArray    = $sliceData['isArray'];
            $isRequired = $sliceData['isRequired'];
            $shortDesc  = $sliceData['shortDesc'];

            if ($isArray) {
                $shortType = $phpType !== null ? (string) u($phpType)->afterLast('\\') : 'mixed';
                $param     = $constructor->addPromotedParameter($paramName)
                    ->setPublic()
                    ->setType('array')
                    ->setNullable(false)
                    ->setDefaultValue([]);

                if ($sliceCode !== null) {
                    $param->addAttribute(FhirProperty::class, [
                        'fhirType'     => $sliceCode,
                        'propertyKind' => $this->resolvePropertyKindFromCode($sliceCode),
                        'isArray'      => true,
                    ]);
                }

                $arrayDocType = $phpType ?? 'mixed';
                $param->addComment("@var array<{$arrayDocType}> {$paramName} {$shortDesc}");

                $bodyLines[] = "foreach (\$this->{$paramName} as \$v) {";
                $bodyLines[] = "    \$subExtensions[] = new \\{$extensionFqcn}(url: '{$sliceName}', value: \$v);";
                $bodyLines[] = '}';
            } else {
                $resolvedType = $phpType ?? 'string';
                $shortType    = (string) u($resolvedType)->afterLast('\\');
                $nullability  = !$isRequired;

                $param = $constructor->addPromotedParameter($paramName)
                    ->setPublic()
                    ->setType($resolvedType)
                    ->setNullable($nullability);

                if ($nullability) {
                    $param->setDefaultValue(null);
                }

                if ($sliceCode !== null) {
                    $param->addAttribute(FhirProperty::class, [
                        'fhirType'     => $sliceCode,
                        'propertyKind' => $this->resolvePropertyKindFromCode($sliceCode),
                    ]);
                }

                $nullableDoc = $nullability ? '|null' : '';
                $param->addComment("@var {$resolvedType}{$nullableDoc} {$paramName} {$shortDesc}");

                if ($nullability) {
                    $bodyLines[] = "if (\$this->{$paramName} !== null) {";
                    $bodyLines[] = "    \$subExtensions[] = new \\{$extensionFqcn}(url: '{$sliceName}', value: \$this->{$paramName});";
                    $bodyLines[] = '}';
                } else {
                    $bodyLines[] = "\$subExtensions[] = new \\{$extensionFqcn}(url: '{$sliceName}', value: \$this->{$paramName});";
                }
            }
        }

        $constructor->addParameter('id')
            ->setType('string')
            ->setNullable(true)
            ->setDefaultValue(null);

        $bodyLines[] = 'parent::__construct(';
        $bodyLines[] = '    id: $id,';
        $bodyLines[] = '    extension: $subExtensions,';
        $bodyLines[] = "    url: '{$url}',";
        $bodyLines[] = ');';

        $constructor->setBody(implode("\n", $bodyLines));
    }

    /**
     * Generate the static fromSubExtensions() factory method for a complex extension.
     *
     * The generated method reconstructs a typed extension instance from an array of
     * already-denormalized sub-extension objects (each implementing FHIRExtensionInterface)
     * by matching each sub-extension's URL to the appropriate typed parameter.
     *
     * @param array<int, mixed> $elements
     */
    private function buildFromSubExtensionsMethod(
        ClassType $class,
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $url,
        ?ErrorCollector $errorCollector = null,
    ): void {
        $slices = $this->collectSlices($elements, $version, $context, $namespace, $errorCollector);

        $method = $class->addMethod('fromSubExtensions')
            ->setStatic(true)
            ->setReturnType('static')
            ->setVisibility('public');

        $method->addParameter('subExtensions')->setType('array');
        $method->addParameter('id')
            ->setType('string')
            ->setNullable(true)
            ->setDefaultValue(null);

        $method->addComment('Reconstruct from an array of already-denormalized sub-extension objects.');
        $method->addComment('@param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions');
        $method->addComment('@param string|null $id');

        // Initialise parameter variables
        $lines = [];
        foreach ($slices as $slice) {
            $paramName = $slice['paramName'];
            $lines[]   = "\${$paramName} = " . ($slice['isArray'] ? '[]' : 'null') . ';';
        }

        if (!empty($slices)) {
            $lines[] = '';
            $lines[] = 'foreach ($subExtensions as $ext) {';
            $lines[] = '    $extUrl = $ext->getExtensionUrl();';

            foreach ($slices as $slice) {
                $sliceName  = $slice['sliceName'];
                $paramName  = $slice['paramName'];
                $phpType    = $slice['phpType'];
                $isArray    = $slice['isArray'];
                $typeCheck  = $this->buildTypeCheck('$ext->value', $phpType);

                if ($isArray) {
                    $lines[] = "    if (\$extUrl === '{$sliceName}' && {$typeCheck}) {";
                    $lines[] = "        \${$paramName}[] = \$ext->value;";
                    $lines[] = '    }';
                } else {
                    $lines[] = "    if (\$extUrl === '{$sliceName}' && {$typeCheck}) {";
                    $lines[] = "        \${$paramName} = \$ext->value;";
                    $lines[] = '    }';
                }
            }

            $lines[] = '}';
            $lines[] = '';
        }

        // Guard: required (non-nullable, non-array) slices must be matched; throw a clear error
        // rather than letting PHP produce a cryptic TypeError inside new static().
        foreach ($slices as $slice) {
            if ($slice['isRequired'] && !$slice['isArray']) {
                $paramName  = $slice['paramName'];
                $sliceName  = $slice['sliceName'];
                $lines[]    = "if (\${$paramName} === null) {";
                $lines[]    = '    throw new \\InvalidArgumentException(';
                $lines[]    = "        'Required sub-extension \"{$sliceName}\" not found or type mismatch in ' . static::class . '::fromSubExtensions()',";
                $lines[]    = '    );';
                $lines[]    = '}';
            }
        }

        $args    = array_map(static fn (array $s): string => "\${$s['paramName']}", $slices);
        $args[]  = '$id';
        $lines[] = 'return new static(' . implode(', ', $args) . ');';

        $method->setBody(implode("\n", $lines));
    }

    /**
     * Collect all slice definitions from a snapshot element list and resolve their PHP types.
     *
     * Returns structured slice data used by both buildComplexConstructor() and
     * buildFromSubExtensionsMethod() to avoid duplicating the type-resolution logic.
     *
     * @param array<int, mixed> $elements
     *
     * @return list<array{sliceName: string, paramName: string, phpType: string|null, sliceCode: string|null, isArray: bool, isRequired: bool, shortDesc: string}>
     */
    private function collectSlices(
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        ?ErrorCollector $errorCollector = null,
    ): array {
        $rawSlices = [];
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            if (($element['path'] ?? '') === 'Extension.extension' && isset($element['sliceName'])) {
                $rawSlices[] = $element;
            }
        }

        $result = [];
        foreach ($rawSlices as $slice) {
            $sliceName = $slice['sliceName'];
            $maxValue  = $slice['max'] ?? '1';
            $minValue  = (int) ($slice['min'] ?? 0);
            $isArray   = !in_array($maxValue, ['0', '1'], true);
            $sliceId   = $slice['id'] ?? "Extension.extension:{$sliceName}";

            $sliceValueElement = $this->findSliceValueElement($elements, $sliceId);
            $sliceCode         = null;
            $phpType           = null;

            if ($sliceValueElement !== null && !empty($sliceValueElement['type'])) {
                $sliceCode = $sliceValueElement['type'][0]['code'] ?? null;
                if ($sliceCode !== null) {
                    $phpType = $this->resolvePhpType($sliceCode, $version, $context, $errorCollector);
                    if ($phpType !== 'bool' && $phpType !== 'int' && $phpType !== 'string') {
                        $namespace->addUse(ltrim($phpType, '\\'));
                    }
                }
            }

            $result[] = [
                'sliceName'  => $sliceName,
                'paramName'  => $this->resolveSliceParamName((string) u($sliceName)->camel()),
                'phpType'    => $phpType,
                'sliceCode'  => $sliceCode,
                'isArray'    => $isArray,
                'isRequired' => $minValue >= 1,
                'shortDesc'  => $slice['short'] ?? $sliceName,
            ];
        }

        return $result;
    }

    /**
     * Resolve a camel-cased slice name to a safe PHP parameter/property name.
     *
     * Slice names that match parent Extension property names (value, url, id, extension)
     * would cause a compile error due to PHP's property type invariance rules.
     * Such names are suffixed with "Slice" to avoid the conflict.
     */
    private function resolveSliceParamName(string $camelName): string
    {
        static $reserved = ['value', 'url', 'id', 'extension'];

        return in_array($camelName, $reserved, true) ? $camelName . 'Slice' : $camelName;
    }

    /**
     * Build a PHP boolean type-check expression for use in the fromSubExtensions body.
     *
     * PHP scalar types (bool, int, string) use is_* checks; class types use instanceof.
     * A null $phpType (unresolvable) falls back to a null-check only.
     */
    private function buildTypeCheck(string $varExpr, ?string $phpType): string
    {
        if ($phpType === null) {
            return "{$varExpr} !== null";
        }

        return match ($phpType) {
            'bool'   => "is_bool({$varExpr})",
            'int'    => "is_int({$varExpr})",
            'string' => "is_string({$varExpr})",
            default  => "{$varExpr} instanceof \\" . ltrim($phpType, '\\'),
        };
    }

    /**
     * Find the value[x] element in a snapshot element list.
     *
     * Returns null if value[x] is missing, absent, or max=0 (suppressed).
     *
     * @param array<int, mixed> $elements
     *
     * @return array<string, mixed>|null
     */
    private function findValueElement(array $elements): ?array
    {
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            if (($element['path'] ?? '') === 'Extension.value[x]'
                && ($element['max'] ?? '1') !== '0'
            ) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Find the value[x] element for a specific sub-extension slice.
     *
     * Slices use an element id like "Extension.extension:ombCategory.value[x]".
     * We match by id prefix or by path+sliceName context.
     *
     * @param array<int, mixed> $elements
     * @param string            $sliceId  The slice element id (e.g. "Extension.extension:ombCategory")
     *
     * @return array<string, mixed>|null
     */
    private function findSliceValueElement(array $elements, string $sliceId): ?array
    {
        $valueIdPattern = $sliceId . '.value[x]';

        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            $elementId = $element['id'] ?? '';

            if ($elementId === $valueIdPattern) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Resolve a FHIR type code to its PHP type string (FQCN with leading backslash, or scalar).
     *
     * Checks the BuilderContext first (covers any type already generated), then falls
     * back to the known namespace convention for primitive and data types.
     */
    private function resolvePhpType(string $code, string $version, BuilderContext $context, ?ErrorCollector $errorCollector = null): string
    {
        return match ($code) {
            'boolean'                                 => 'bool',
            'integer'                                 => 'int',
            'decimal'                                 => 'string',
            'http://hl7.org/fhirpath/System.Boolean'  => 'bool',
            'http://hl7.org/fhirpath/System.Integer'  => 'int',
            'http://hl7.org/fhirpath/System.Decimal'  => 'string',
            'http://hl7.org/fhirpath/System.String'   => 'string',
            default                                   => $this->resolveObjectPhpType($code, $version, $context, $errorCollector),
        };
    }

    /**
     * Resolve a non-scalar FHIR type code to a PHP class FQCN.
     *
     * When the type URL is not found in the BuilderContext (e.g. it belongs to a dependency
     * package that could not be loaded), a pascal-cased fallback FQCN is returned and a
     * warning is recorded via the ErrorCollector so the caller can surface it to the user.
     */
    private function resolveObjectPhpType(string $code, string $version, BuilderContext $context, ?ErrorCollector $errorCollector = null): string
    {
        // Normalize to URL for context lookup
        $url = str_starts_with($code, 'http://') || str_starts_with($code, 'https://')
            ? $code
            : 'http://hl7.org/fhir/StructureDefinition/' . $code;

        $info = $context->getType($url);
        if ($info !== null) {
            return $info->fqcn; // Already includes leading backslash
        }

        $baseNs = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";

        if (in_array($code, self::PRIMITIVE_TYPES, true)) {
            $className = u($code)->pascal()->toString() . 'Primitive';

            return "\\{$baseNs}\\Primitive\\{$className}";
        }

        // Fallback: produce a valid pascal-cased PHP identifier and warn so the user knows
        // this type could not be resolved (the package providing it may not be installed).
        $className    = u($code)->pascal()->toString();
        $fallbackFqcn = "\\{$baseNs}\\DataType\\{$className}";

        $errorCollector?->addWarning(
            "Could not resolve type URL '{$url}' — using fallback FQCN '{$fallbackFqcn}'. "
            . 'Ensure the package providing this type is included in your --package list.',
            $url,
        );

        return $fallbackFqcn;
    }

    /**
     * Map a FHIR type code to a property kind string (mirrors FHIRModelGenerator logic).
     */
    private function resolvePropertyKindFromCode(string $code): string
    {
        if (str_starts_with($code, 'http://hl7.org/fhirpath/System.')) {
            return 'scalar';
        }

        if (in_array($code, ['boolean', 'integer', 'decimal'], true)) {
            return 'scalar';
        }

        if (in_array($code, self::PRIMITIVE_TYPES, true)) {
            return 'primitive';
        }

        return 'complex';
    }
}
