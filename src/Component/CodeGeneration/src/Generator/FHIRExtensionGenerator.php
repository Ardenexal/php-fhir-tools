<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

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
     * @param BuilderContext        $context             Builder context for type resolution
     * @param PhpNamespace          $namespace           Target namespace for the generated class
     *
     * @return ClassType The generated PHP class
     */
    public function generate(
        array $structureDefinition,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
    ): ClassType {
        $url       = $structureDefinition['url'] ?? '';
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
            $this->buildComplexConstructor($constructor, $elements, $version, $context, $namespace, $extensionFqcn, $url);
        } else {
            $this->buildSimpleConstructor($constructor, $elements, $version, $context, $namespace, $url);
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
        \Nette\PhpGenerator\Method $constructor,
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $url,
    ): void {
        // Find the value[x] element
        $valueElement = $this->findValueElement($elements);

        if ($valueElement !== null) {
            $types = $valueElement['type'] ?? [];

            if (count($types) === 1) {
                // Single concrete type: generate a named, typed property
                $code      = $types[0]['code'] ?? 'string';
                $phpType   = $this->resolvePhpType($code, $version, $context);
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
                $param->addComment("@var {$shortType}|null {$paramName} {$shortDesc}");

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
                    ");"
                );

                return;
            }

            if (count($types) > 1) {
                // Multiple allowed types: expose as a generic value property
                $this->buildMultiTypeValueConstructor($constructor, $types, $version, $context, $namespace, $url);

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
            ");"
        );
    }

    /**
     * Build a constructor for a simple extension with multiple allowed value types.
     *
     * @param list<array{code: string}> $types
     */
    private function buildMultiTypeValueConstructor(
        \Nette\PhpGenerator\Method $constructor,
        array $types,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $url,
    ): void {
        // Union of all allowed PHP types
        $phpTypes  = [];
        $fhirTypes = [];

        foreach ($types as $t) {
            $code        = $t['code'];
            $phpTypes[]  = $this->resolvePhpType($code, $version, $context);
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

        $param = $constructor->addPromotedParameter('value')
            ->setPublic()
            ->setType($phpTypes[0])
            ->setNullable(true)
            ->setDefaultValue(null);
        $param->addAttribute(FhirProperty::class, [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isChoice'     => true,
        ]);
        $param->addComment("@var {$shortTypes}|null value Value of extension ({$unionType})");

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
            "    value: \$this->value,\n" .
            ");"
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
        \Nette\PhpGenerator\Method $constructor,
        array $elements,
        string $version,
        BuilderContext $context,
        PhpNamespace $namespace,
        string $extensionFqcn,
        string $url,
    ): void {
        // Collect slice definitions (Extension.extension elements with a sliceName)
        $slices = [];
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            if (($element['path'] ?? '') === 'Extension.extension' && isset($element['sliceName'])) {
                $slices[] = $element;
            }
        }

        $bodyLines = ['$subExtensions = [];'];

        foreach ($slices as $slice) {
            $sliceName  = $slice['sliceName'];
            $maxValue   = $slice['max'] ?? '1';
            $minValue   = (int) ($slice['min'] ?? 0);
            $isArray    = !in_array($maxValue, ['0', '1'], true);
            $isRequired = $minValue >= 1;
            $shortDesc  = $slice['short'] ?? $sliceName;
            $sliceId    = $slice['id'] ?? "Extension.extension:{$sliceName}";

            // Find this slice's value[x] element to determine its PHP type
            $sliceValueElement = $this->findSliceValueElement($elements, $sliceId);
            $sliceCode         = null;
            $phpType           = null;

            if ($sliceValueElement !== null && !empty($sliceValueElement['type'])) {
                $sliceCode = $sliceValueElement['type'][0]['code'] ?? null;
                if ($sliceCode !== null) {
                    $phpType = $this->resolvePhpType($sliceCode, $version, $context);
                    if ($phpType !== 'bool' && $phpType !== 'int' && $phpType !== 'string') {
                        $namespace->addUse(ltrim($phpType, '\\'));
                    }
                }
            }

            $paramName = (string) u($sliceName)->camel();

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

                $param->addComment("@var array<{$shortType}> {$paramName} {$shortDesc}");

                $bodyLines[] = "foreach (\$this->{$paramName} as \$v) {";
                $bodyLines[] = "    \$subExtensions[] = new Extension(url: '{$sliceName}', value: \$v);";
                $bodyLines[] = "}";
            } else {
                $resolvedType  = $phpType ?? 'string';
                $shortType     = (string) u($resolvedType)->afterLast('\\');
                $nullability   = !$isRequired;

                $param = $constructor->addPromotedParameter($paramName)
                    ->setPublic()
                    ->setType($resolvedType)
                    ->setNullable($nullability)
                    ->setDefaultValue($nullability ? null : '');

                if ($sliceCode !== null) {
                    $param->addAttribute(FhirProperty::class, [
                        'fhirType'     => $sliceCode,
                        'propertyKind' => $this->resolvePropertyKindFromCode($sliceCode),
                    ]);
                }

                $nullableDoc = $nullability ? '|null' : '';
                $param->addComment("@var {$shortType}{$nullableDoc} {$paramName} {$shortDesc}");

                if ($nullability) {
                    $bodyLines[] = "if (\$this->{$paramName} !== null) {";
                    $bodyLines[] = "    \$subExtensions[] = new Extension(url: '{$sliceName}', value: \$this->{$paramName});";
                    $bodyLines[] = "}";
                } else {
                    $bodyLines[] = "\$subExtensions[] = new Extension(url: '{$sliceName}', value: \$this->{$paramName});";
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
    private function resolvePhpType(string $code, string $version, BuilderContext $context): string
    {
        return match ($code) {
            'boolean'                          => 'bool',
            'integer'                          => 'int',
            'decimal'                          => 'string',
            'http://hl7.org/fhirpath/System.Boolean'  => 'bool',
            'http://hl7.org/fhirpath/System.Integer'  => 'int',
            'http://hl7.org/fhirpath/System.Decimal'  => 'string',
            'http://hl7.org/fhirpath/System.String'   => 'string',
            default                            => $this->resolveObjectPhpType($code, $version, $context),
        };
    }

    /**
     * Resolve a non-scalar FHIR type code to a PHP class FQCN.
     */
    private function resolveObjectPhpType(string $code, string $version, BuilderContext $context): string
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

        // Assume DataType for unknown complex types
        return "\\{$baseNs}\\DataType\\{$code}";
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
