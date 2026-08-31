<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Support\CanonicalUrl;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;
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
     * @param list<string>               $propertyOrder           Every property name this class can serialize, own and
     *                                                            inherited, in published content-model order. Recorded
     *                                                            on the class attribute because neither reflection nor
     *                                                            the class hierarchy can reconstruct it: own properties
     *                                                            reflect before inherited ones, while the content model
     *                                                            puts a CDA parent's `realmCode`/`typeId`/`templateId`
     *                                                            first and can place a child's own element mid-sequence.
     *                                                            Omitted from the attribute when empty.
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
        array $propertyOrder = [],
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

        // `type` naming a different generatable class is precisely the refinement signal: this
        // definition constrains a type someone else published rather than introducing one.
        $refines = ($type !== '' && $type !== $url && isset($urlToFqcn[$type])) ? $type : null;

        if ($refines !== null) {
            $class->setExtends($urlToFqcn[$refines]);
        } elseif ($baseDefinition !== null && isset($urlToFqcn[$baseDefinition])) {
            $class->setExtends($urlToFqcn[$baseDefinition]);
        }

        // `refines` is recorded so serializers can resolve the wire element name by following the
        // chain to the type that actually named the element. `name` cannot answer that on its own:
        // for a refinement it is a profile identifier (`au-ClinicalDocument`), which is no element
        // name — CDA requires `<ClinicalDocument>`. Emitting the relationship rather than a resolved
        // name keeps the generated model to what the StructureDefinition states, leaving the naming
        // policy in the serializer where it can be corrected without regenerating.
        $arguments = [
            'url'          => $url,
            'name'         => $name,
            'fhirVersion'  => (string) ($definition['fhirVersion'] ?? '5.0.0'),
            'xmlNamespace' => $xmlNamespace,
        ];

        // Omitted rather than emitted as `refines: null`, which the attribute already defaults to:
        // most definitions refine nothing (179 of the 247 CDA classes), so emitting it would add a
        // line of noise to each of them that says only what its absence says.
        if ($refines !== null) {
            $arguments['refines'] = $refines;
        }

        // Omitted rather than emitted as an empty list, matching `refines`: an empty list is the
        // attribute's default and means "no ordering opinion", which serializers read as a signal to
        // keep their previous behaviour.
        if ($propertyOrder !== []) {
            $arguments['propertyOrder'] = $propertyOrder;
        }

        $class->addAttribute(LogicalModel::class, $arguments);

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
            $descriptor = $this->deriveParameter($element, $urlToFqcn, $classXmlNamespace, $valueSetToEnumFqcn, $elements);
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
     * @param array<mixed>          $siblings           all elements of the owning snapshot, so a transparent
     *                                                  choice group can read its own child slices
     *
     * @return array<string, mixed>|null
     */
    private function deriveParameter(
        array $element,
        array $urlToFqcn,
        string $classXmlNamespace,
        array $valueSetToEnumFqcn = [],
        array $siblings = [],
    ): ?array {
        $path          = (string) ($element['path'] ?? '');
        $parameterName = self::propertyNameFromPath($path);
        if ($parameterName === '') {
            return null;
        }

        $max     = (string) ($element['max'] ?? '1');
        $min     = (int) ($element['min'] ?? 0);
        $isArray = !in_array($max, ['0', '1'], true);

        $typeCode = '';
        $types    = $element['type'] ?? [];
        if (is_array($types) && isset($types[0]) && is_array($types[0])) {
            $typeCode = (string) ($types[0]['code'] ?? '');
        }

        // Transparent choice group: the element's children emit directly under the parent, with no
        // wrapper element, and their document order is significant. Handled before the type
        // resolution below because the element's own declared type is the FHIR `Base` marker, which
        // would otherwise fall through to the scalar-string default and drop the slices entirely.
        if ($this->hasChoiceGroupExtension($element)) {
            $variants = $this->choiceGroupVariants($path, $siblings, $urlToFqcn);
            if ($variants !== []) {
                return [
                    'name'        => $parameterName,
                    'phpType'     => 'array',
                    'isArray'     => true,
                    'itemType'    => '\\' . ChoiceGroupItem::class,
                    'fixedScalar' => null,
                    'fhirArgs'    => [
                        'fhirType'     => $typeCode !== '' ? $typeCode : 'string',
                        'propertyKind' => 'choiceGroup',
                        'isArray'      => true,
                        'isRequired'   => $min >= 1,
                        'phpType'      => '\\' . ChoiceGroupItem::class,
                        'variants'     => $variants,
                    ],
                ];
            }
        }

        // Resolve the property's PHP type and semantic kind from the type code read above.
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
     * True when an element carries the FHIR tooling `xml-choice-group` extension, marking it a
     * transparent group whose children emit directly under the parent with no wrapper element.
     *
     * @param array<string, mixed> $element
     */
    private function hasChoiceGroupExtension(array $element): bool
    {
        $extensions = $element['extension'] ?? [];
        if (!is_array($extensions)) {
            return false;
        }
        foreach ($extensions as $extension) {
            if (!is_array($extension) || !str_contains((string) ($extension['url'] ?? ''), 'xml-choice-group')) {
                continue;
            }

            // The extension is a boolean flag; an explicit false disables the group.
            return ($extension['valueBoolean'] ?? true) !== false;
        }

        return false;
    }

    /**
     * Build the variant list for a transparent choice group: one entry per direct child slice, keyed
     * by the child's local XML element name, which is the discriminator the serializer dispatches on.
     *
     * These groups are mixed element-and-text, so one slice per group is character data rather than
     * an element (`EN.item.xmlText`, `AD.item.xmlText`, …). It is marked by `representation: xmlText`
     * — the same mechanism `xmlAttr` uses above — and gets a string variant under the reserved key
     * {@see ChoiceGroupItem::TEXT_ELEMENT_NAME}, which the serializer emits as the parent's text
     * content rather than as a child element. The group's own invariant treats it as a peer of the
     * element slices (`EN-1`: `(delimiter | family | given | prefix | suffix | xmlText).count() = 1`).
     *
     * The marker is the only signal read. Matching on the slice's local name instead would silently
     * capture a future element genuinely named `xmlText`; per the published CDA IG no element by
     * that name appears in an instance, so the marked slice is unambiguous.
     *
     * @param array<mixed>          $siblings
     * @param array<string, string> $urlToFqcn
     *
     * @return list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string}>
     */
    private function choiceGroupVariants(string $path, array $siblings, array $urlToFqcn): array
    {
        $depth    = substr_count($path, '.') + 1;
        $prefix   = $path . '.';
        $variants = [];

        foreach ($siblings as $sibling) {
            if (!is_array($sibling)) {
                continue;
            }
            $siblingPath = (string) ($sibling['path'] ?? '');
            if (!str_starts_with($siblingPath, $prefix) || substr_count($siblingPath, '.') !== $depth) {
                continue;
            }

            $localName = self::propertyNameFromPath($siblingPath);
            if ($localName === '') {
                continue;
            }

            $representations = $sibling['representation'] ?? [];
            if (is_array($representations) && in_array('xmlText', $representations, true)) {
                $variants[] = [
                    'fhirType'     => 'string',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'string',
                    'jsonKey'      => ChoiceGroupItem::TEXT_ELEMENT_NAME,
                ];

                continue;
            }

            $types    = $sibling['type'] ?? [];
            $typeCode = (is_array($types) && isset($types[0]) && is_array($types[0]))
                ? (string) ($types[0]['code'] ?? '')
                : '';
            [$phpType, $propertyKind] = $this->resolveType($typeCode, $urlToFqcn);

            $variants[] = [
                'fhirType'     => $typeCode !== '' ? $typeCode : 'string',
                'propertyKind' => $propertyKind,
                'phpType'      => $phpType,
                'jsonKey'      => $localName,
            ];
        }

        return $variants;
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
        $valueSet = CanonicalUrl::stripVersion($valueSet);

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
