<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Method;

use function Symfony\Component\String\u;

/**
 * Generates a PHP class from a logical-model StructureDefinition (kind=logical,
 * derivation=specialization) — e.g. the HL7 CDA R2 datatypes and act/role/entity classes.
 *
 * This generator is intentionally IG-agnostic (see ADR-008): it consumes the generic FHIR
 * StructureDefinition fields (kind, derivation, baseDefinition, snapshot.element) and does not
 * branch on "is this CDA". CDA-specific identity (output namespace, urn:hl7-org:v3) is supplied
 * by the caller via the target namespace and the resolved xmlNamespace.
 *
 * Parent (`extends`) and property types are resolved from a pre-computed url → FQCN map rather
 * than from the BuilderContext, so generation order does not matter: CDA references every type by
 * its full canonical URL (e.g. ClinicalDocument.code : .../StructureDefinition/CE), and the map
 * covers all generatable types up front.
 */
final class LogicalModelGenerator
{
    /**
     * FHIR primitive type codes that appear as CDA scalar (usually xmlAttr) leaf values, mapped to
     * the native PHP type they serialize as. Anything not listed falls back to `string`.
     *
     * @var array<string, string>
     */
    private const array PRIMITIVE_TO_PHP = [
        'boolean'      => 'bool',
        'integer'      => 'int',
        'int'          => 'int',
        'positiveInt'  => 'int',
        'unsignedInt'  => 'int',
        'decimal'      => 'float',
    ];

    /**
     * Derive the PHP constructor-property name for an element path: the segment after the last dot,
     * with any `[x]` choice marker stripped, camel-cased. e.g. `ClinicalDocument.realmCode` → `realmCode`.
     */
    public static function propertyNameFromPath(string $path): string
    {
        $dot     = strrpos($path, '.');
        $segment = $dot === false ? $path : substr($path, $dot + 1);

        return u(str_replace('[x]', '', $segment))->camel()->toString();
    }

    /**
     * @param array<string, mixed>       $definition              The logical-model StructureDefinition
     * @param array<string, string>      $urlToFqcn               Canonical SD URL → leading-backslash PHP FQCN,
     *                                                            for every generatable logical model in the package
     * @param list<string>               $inheritedNames          Property names already declared by an ancestor;
     *                                                            skipped here so PHP property-type invariance holds
     *                                                            (CDA subclasses re-narrow inherited element types)
     * @param list<string>               $inheritedConstraintKeys Invariant keys already carried by an
     *                                                            ancestor (CDA flattens them into child snapshots);
     *                                                            skipped to avoid double-emitting inherited invariants
     * @param array<string, string>      $valueSetToEnumFqcn      Canonical ValueSet URL → leading-backslash
     *                                                            enum FQCN; a `code`/`cs` property whose binding
     *                                                            resolves here is typed to the enum
     * @param list<array<string, mixed>> $inheritedParams         The parent class's full, ordered constructor
     *                                                            parameter descriptors (from
     *                                                            {@see collectOwnParameters()} threaded through the
     *                                                            parent chain). Re-declared here as non-promoted
     *                                                            params and forwarded via `parent::__construct()`
     *                                                            so inherited promoted properties are initialised —
     *                                                            PHP does NOT run a parent constructor automatically,
     *                                                            so without this every inherited typed property is
     *                                                            uninitialised and throws on access.
     */
    public function generate(
        array $definition,
        PhpNamespace $namespace,
        string $xmlNamespace,
        array $urlToFqcn,
        array $inheritedNames = [],
        array $inheritedConstraintKeys = [],
        array $valueSetToEnumFqcn = [],
        array $inheritedParams = [],
    ): ClassType {
        $url  = (string) ($definition['url'] ?? '');
        $name = (string) ($definition['name'] ?? '');

        $className = ClassNameResolver::logicalModelClassName($url, $name);
        $class     = new ClassType($className, $namespace);

        if (($definition['abstract'] ?? false) === true) {
            $class->setAbstract();
        }

        // Parent resolution. For core CDA types the immediate parent is `baseDefinition`
        // (ANY's base is the FHIR `Base` type, not a generated CDA class, so it resolves to no
        // parent — the abstract root). AU specializations, however, set `type` to the core class
        // they refine (e.g. au-ClinicalDocument.type = .../ClinicalDocument) while `baseDefinition`
        // points at the shared abstract root (ANY / InfrastructureRoot). When `type` names a
        // *different* generatable class, it is the real parent (M2-deferred rule, confirmed
        // against the AU package in M4). Core's own `type != url` cases are hyphen/underscore
        // separator mismatches (url=.../IVL-TS, type=.../IVL_TS) that name the SAME type; those
        // `type` values never key the url-keyed map, so they correctly fall through to
        // `baseDefinition` and core generation is unaffected.
        // Types are referenced as leading-backslash FQCNs (Nette prints them fully-qualified);
        // no `use` management is needed because each class is printed in a fresh namespace.
        $type           = (string) ($definition['type'] ?? '');
        $baseDefinition = isset($definition['baseDefinition']) ? (string) $definition['baseDefinition'] : null;
        if ($type !== '' && $type !== $url && isset($urlToFqcn[$type])) {
            $class->setExtends($urlToFqcn[$type]);
        } elseif ($baseDefinition !== null && isset($urlToFqcn[$baseDefinition])) {
            $class->setExtends($urlToFqcn[$baseDefinition]);
        }

        $class->addAttribute(LogicalModel::class, [
            'url'          => $url,
            'name'         => $name,
            'fhirVersion'  => (string) ($definition['fhirVersion'] ?? '5.0.0'),
            'xmlNamespace' => $xmlNamespace,
        ]);

        $this->addInvariants($class, $definition, $url, $inheritedConstraintKeys);

        $constructor = $class->addMethod('__construct');

        // Own (non-inherited) properties → promoted constructor params carrying the FhirProperty
        // attribute. Inherited elements are skipped here (declared on the parent) and instead
        // forwarded to the parent constructor below.
        $ownParams = $this->collectOwnParameters($definition, $urlToFqcn, $xmlNamespace, $inheritedNames, $valueSetToEnumFqcn);
        foreach ($ownParams as $descriptor) {
            $this->promoteParameter($constructor, $descriptor);
        }

        // Inherited properties → non-promoted passthrough params + parent::__construct(), so the
        // parent's promoted properties are actually initialised (PHP runs no parent constructor
        // implicitly). Named arguments keep the call order-independent. Roots (no resolvable
        // parent) have no inherited params and emit no call.
        foreach ($inheritedParams as $descriptor) {
            $this->declareInheritedParameter($constructor, $descriptor);
        }
        if ($inheritedParams !== []) {
            $constructor->addBody($this->parentConstructorCall($inheritedParams));
        }

        return $class;
    }

    /**
     * Emit one `#[FHIRPathInvariant]` per root-element constraint that carries a FHIRPath
     * `expression`. Skips XPath-only constraints, constraints whose `source` names a different SD,
     * and keys already carried by an ancestor (CDA inlines inherited constraints into child
     * snapshots — the parent PHP class already declares them).
     *
     * @param array<string, mixed> $definition
     * @param list<string>         $inheritedConstraintKeys
     */
    private function addInvariants(ClassType $class, array $definition, string $url, array $inheritedConstraintKeys): void
    {
        $rootElement = $definition['snapshot']['element'][0] ?? null;
        if (!is_array($rootElement)) {
            return;
        }
        $constraints = $rootElement['constraint'] ?? [];
        if (!is_array($constraints)) {
            return;
        }

        foreach ($constraints as $constraint) {
            if (!is_array($constraint)) {
                continue;
            }
            $expression = (string) ($constraint['expression'] ?? '');
            if ($expression === '') {
                continue;
            }
            $source = $constraint['source'] ?? null;
            if ($source !== null && (string) $source !== $url) {
                continue;
            }
            $key = (string) ($constraint['key'] ?? '');
            if ($key !== '' && in_array($key, $inheritedConstraintKeys, true)) {
                continue;
            }
            $class->addAttribute(FHIRPathInvariant::class, [
                'key'        => $key,
                'severity'   => (string) ($constraint['severity'] ?? 'error'),
                'expression' => $expression,
                'human'      => (string) ($constraint['human'] ?? ''),
            ]);
        }
    }

    /**
     * Collect the ordered constructor-parameter descriptors for a class's OWN (non-inherited)
     * direct-child elements. Pure (no class mutation) so the caller can both promote them onto this
     * class AND thread the parent chain's descriptors into children for `parent::__construct()`
     * forwarding. Deep paths (>1 dot) and elements already declared by an ancestor are skipped.
     *
     * @param array<string, mixed>  $definition
     * @param array<string, string> $urlToFqcn
     * @param list<string>          $inheritedNames
     * @param array<string, string> $valueSetToEnumFqcn
     *
     * @return list<array<string, mixed>>
     */
    public function collectOwnParameters(
        array $definition,
        array $urlToFqcn,
        string $classXmlNamespace,
        array $inheritedNames = [],
        array $valueSetToEnumFqcn = [],
    ): array {
        $params   = [];
        $elements = $definition['snapshot']['element'] ?? [];
        if (!is_array($elements)) {
            return [];
        }
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }
            $path = (string) ($element['path'] ?? '');
            // Direct children only (exactly one dot). Deeper paths (e.g. typeId.root) are
            // cardinality/fixed refinements of an already-typed property and are deferred.
            if (substr_count($path, '.') !== 1) {
                continue;
            }
            // Skip elements already declared by an ancestor — they are forwarded to the parent
            // constructor instead. Re-promoting them would violate PHP property-type invariance
            // where a CDA subclass re-narrows an inherited element (e.g. CV.translation vs
            // CE.translation).
            if (in_array(self::propertyNameFromPath($path), $inheritedNames, true)) {
                continue;
            }
            $descriptor = $this->deriveParameter($element, $urlToFqcn, $classXmlNamespace, $valueSetToEnumFqcn);
            if ($descriptor !== null) {
                $params[] = $descriptor;
            }
        }

        return $params;
    }

    /**
     * Derive a single constructor-parameter descriptor from an element, or null when the element
     * has no usable property name. The descriptor carries everything needed to either promote the
     * parameter (own property, with the FhirProperty attribute) or re-declare it as a non-promoted
     * passthrough (inherited property, no attribute).
     *
     * @param array<string, mixed>  $element
     * @param array<string, string> $urlToFqcn
     * @param array<string, string> $valueSetToEnumFqcn
     *
     * @return array<string, mixed>|null
     */
    private function deriveParameter(
        array $element,
        array $urlToFqcn,
        string $classXmlNamespace,
        array $valueSetToEnumFqcn = [],
    ): ?array {
        $path          = (string) ($element['path'] ?? '');
        $parameterName = self::propertyNameFromPath($path);
        if ($parameterName === '') {
            return null;
        }

        $max     = (string) ($element['max'] ?? '1');
        $min     = (int) ($element['min'] ?? 0);
        $isArray = !in_array($max, ['0', '1'], true);

        // Resolve the property's PHP type and semantic kind from the first type code.
        $typeCode    = '';
        $types       = $element['type'] ?? [];
        if (is_array($types) && isset($types[0]) && is_array($types[0])) {
            $typeCode = (string) ($types[0]['code'] ?? '');
        }
        [$phpType, $propertyKind, $itemFqcn] = $this->resolveType($typeCode, $urlToFqcn);

        // XML attribute representation → '@'-prefixed serialized name (read by the XML serializer).
        $representations   = $element['representation'] ?? [];
        $xmlSerializedName = is_array($representations) && in_array('xmlAttr', $representations, true)
            ? '@' . $parameterName
            : null;

        // Fixed scalar values (e.g. classCode=DOCCLIN, moodCode=EVN) become PHP defaults.
        $fixedScalar = null;
        $fixed       = ElementDefinitionHelper::extractPolymorphicField($element, 'fixed');
        if ($fixed !== null && is_scalar($fixed['value'])) {
            $fixedScalar = $fixed['value'];
        }

        // Coded property (code/cs) with a binding to a generated CDA enum, and no fixed value →
        // type the property to the enum. Fixed-valued coded attributes keep their scalar default
        // (the fixed code is the source of truth; mapping it back to an enum case is deferred).
        if (
            $fixedScalar === null
            && in_array($typeCode, ['code', 'cs'], true)
            && ($enumFqcn = $this->resolveBoundEnum($element, $valueSetToEnumFqcn)) !== null
        ) {
            $phpType      = $enumFqcn;
            $propertyKind = 'enum';
            $itemFqcn     = $isArray ? $enumFqcn : null;
        }

        // Per-element XML namespace, recorded ONLY when it differs from the class namespace (AU CDA
        // extension elements carry the ADHA extension namespace; core elements just repeat the
        // class's urn:hl7-org:v3 and must NOT emit a redundant override — that would churn core
        // output). Recorded for the serializer; wiring is CDA M5.
        $elementXmlNamespace = $this->readElementXmlNamespace($element);
        if ($elementXmlNamespace === $classXmlNamespace) {
            $elementXmlNamespace = null;
        }

        $attributeArgs = [
            'fhirType'     => $typeCode !== '' ? $typeCode : 'string',
            'propertyKind' => $propertyKind,
            'isArray'      => $isArray,
            'isRequired'   => $min >= 1,
        ];
        if ($xmlSerializedName !== null) {
            $attributeArgs['xmlSerializedName'] = $xmlSerializedName;
        }
        if ($isArray && $itemFqcn !== null) {
            $attributeArgs['phpType'] = $itemFqcn;
        }
        if ($elementXmlNamespace !== null) {
            $attributeArgs['xmlNamespace'] = $elementXmlNamespace;
        }

        return [
            'name'        => $parameterName,
            'phpType'     => $phpType,
            'isArray'     => $isArray,
            'itemType'    => $itemFqcn ?? $phpType,
            'fixedScalar' => $fixedScalar,
            'fhirArgs'    => $attributeArgs,
        ];
    }

    /**
     * Add an own property as a promoted constructor parameter carrying its FhirProperty attribute.
     *
     * @param array<string, mixed> $descriptor
     */
    private function promoteParameter(Method $constructor, array $descriptor): void
    {
        $name = (string) $descriptor['name'];
        if ($descriptor['isArray'] === true) {
            $param = $constructor->addPromotedParameter($name, [])->setType('array');
            // PHPStan level 8 requires a value type for iterables.
            $constructor->addComment("@param list<{$descriptor['itemType']}> \${$name}");
        } elseif ($descriptor['fixedScalar'] !== null) {
            $param = $constructor->addPromotedParameter($name, $descriptor['fixedScalar'])->setType((string) $descriptor['phpType']);
        } else {
            $param = $constructor->addPromotedParameter($name, null)->setType((string) $descriptor['phpType'])->setNullable(true);
        }

        /** @var array<string, mixed> $fhirArgs */
        $fhirArgs = $descriptor['fhirArgs'];
        $param->addAttribute(FhirProperty::class, $fhirArgs);
    }

    /**
     * Re-declare an inherited property as a NON-promoted constructor parameter (no FhirProperty
     * attribute — that metadata lives on the parent's promoted property). Its value is forwarded to
     * the parent constructor; see {@see parentConstructorCall()}.
     *
     * @param array<string, mixed> $descriptor
     */
    private function declareInheritedParameter(Method $constructor, array $descriptor): void
    {
        $name      = (string) $descriptor['name'];
        $parameter = $constructor->addParameter($name);
        if ($descriptor['isArray'] === true) {
            $parameter->setType('array')->setDefaultValue([]);
            $constructor->addComment("@param list<{$descriptor['itemType']}> \${$name}");
        } elseif ($descriptor['fixedScalar'] !== null) {
            $parameter->setType((string) $descriptor['phpType'])->setDefaultValue($descriptor['fixedScalar']);
        } else {
            $parameter->setType((string) $descriptor['phpType'])->setNullable(true)->setDefaultValue(null);
        }
    }

    /**
     * Build the `parent::__construct(...)` call body forwarding every inherited parameter by name.
     * Named arguments make the call independent of parameter order.
     *
     * @param list<array<string, mixed>> $inheritedParams
     */
    private function parentConstructorCall(array $inheritedParams): string
    {
        $arguments = array_map(
            static fn (array $descriptor): string => '    ' . $descriptor['name'] . ': $' . $descriptor['name'] . ',',
            $inheritedParams,
        );

        return "parent::__construct(\n" . implode("\n", $arguments) . "\n);";
    }

    /**
     * Resolve the enum FQCN bound to an element, or null. Reads `binding.valueSet`, strips any
     * `|version` suffix, and looks it up in the generated-enum map. Returns null when the element
     * has no binding or the bound ValueSet is not one generated for this package (e.g. an external
     * v3 terminology ValueSet that is not bundled).
     *
     * @param array<string, mixed>  $element
     * @param array<string, string> $valueSetToEnumFqcn
     */
    private function resolveBoundEnum(array $element, array $valueSetToEnumFqcn): ?string
    {
        $binding = $element['binding'] ?? null;
        if (!is_array($binding)) {
            return null;
        }
        $valueSet = $binding['valueSet'] ?? null;
        if (!is_string($valueSet) || $valueSet === '') {
            return null;
        }
        $valueSet = explode('|', $valueSet)[0];

        return $valueSetToEnumFqcn[$valueSet] ?? null;
    }

    /**
     * Read an element's FHIR-tooling xml-namespace extension, or null if absent. AU CDA extension
     * elements declare the ADHA extension namespace (e.g.
     * `http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0`) this way; core CDA elements
     * inherit the class namespace and carry no per-element override.
     *
     * @param array<string, mixed> $element
     */
    private function readElementXmlNamespace(array $element): ?string
    {
        $extensions = $element['extension'] ?? [];
        if (!is_array($extensions)) {
            return null;
        }
        foreach ($extensions as $extension) {
            if (!is_array($extension)) {
                continue;
            }
            if (str_contains((string) ($extension['url'] ?? ''), 'xml-namespace')) {
                $value = $extension['valueUri'] ?? $extension['valueString'] ?? null;

                return $value !== null ? (string) $value : null;
            }
        }

        return null;
    }

    /**
     * Resolve a CDA element type code to [phpType, propertyKind, itemFqcn].
     *
     * - A CDA canonical URL present in the map → the generated class FQCN (propertyKind 'complex').
     * - A FHIR primitive code → a native PHP scalar (propertyKind 'scalar').
     * - Anything else → string (defensive fallback).
     *
     * @param array<string, string> $urlToFqcn
     *
     * @return array{0: string, 1: string, 2: string|null}
     */
    private function resolveType(string $typeCode, array $urlToFqcn): array
    {
        if ($typeCode !== '' && isset($urlToFqcn[$typeCode])) {
            $fqcn = $urlToFqcn[$typeCode];

            return [$fqcn, 'complex', $fqcn];
        }

        $phpType = self::PRIMITIVE_TO_PHP[$typeCode] ?? 'string';

        return [$phpType, 'scalar', null];
    }
}
