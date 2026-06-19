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
     * @param array<string, mixed>  $definition              The logical-model StructureDefinition
     * @param array<string, string> $urlToFqcn               Canonical SD URL → leading-backslash PHP FQCN,
     *                                                       for every generatable logical model in the package
     * @param list<string>          $inheritedNames          Property names already declared by an ancestor;
     *                                                       skipped here so PHP property-type invariance holds
     *                                                       (CDA subclasses re-narrow inherited element types)
     * @param list<string>          $inheritedConstraintKeys Invariant keys already carried by an
     *                                                       ancestor (CDA flattens them into child snapshots);
     *                                                       skipped to avoid double-emitting inherited invariants
     */
    public function generate(
        array $definition,
        PhpNamespace $namespace,
        string $xmlNamespace,
        array $urlToFqcn,
        array $inheritedNames = [],
        array $inheritedConstraintKeys = [],
    ): ClassType {
        $url  = (string) ($definition['url'] ?? '');
        $name = (string) ($definition['name'] ?? '');

        $className = ClassNameResolver::logicalModelClassName($url, $name);
        $class     = new ClassType($className, $namespace);

        if (($definition['abstract'] ?? false) === true) {
            $class->setAbstract();
        }

        // Parent: resolve baseDefinition through the map. ANY's base is the FHIR `Base` type, which
        // is not a generated CDA class, so it resolves to no parent (the abstract root).
        // Types are referenced as leading-backslash FQCNs (Nette prints them fully-qualified);
        // no `use` management is needed because each class is printed in a fresh namespace.
        $baseDefinition = isset($definition['baseDefinition']) ? (string) $definition['baseDefinition'] : null;
        if ($baseDefinition !== null && isset($urlToFqcn[$baseDefinition])) {
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

        $elements = $definition['snapshot']['element'] ?? [];
        if (is_array($elements)) {
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
                // Skip elements already declared by an ancestor — they are inherited via `extends`.
                // Re-promoting them would violate PHP property-type invariance where a CDA subclass
                // re-narrows an inherited element (e.g. CV.translation vs CE.translation).
                if (in_array(self::propertyNameFromPath($path), $inheritedNames, true)) {
                    continue;
                }
                $this->addProperty($constructor, $element, $urlToFqcn);
            }
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
     * @param array<string, mixed>  $element
     * @param array<string, string> $urlToFqcn
     */
    private function addProperty(
        Method $constructor,
        array $element,
        array $urlToFqcn,
    ): void {
        $path          = (string) ($element['path'] ?? '');
        $parameterName = self::propertyNameFromPath($path);
        if ($parameterName === '') {
            return;
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

        if ($isArray) {
            $param = $constructor->addPromotedParameter($parameterName, [])->setType('array');
            // PHPStan level 8 requires a value type for iterables.
            $itemType = $itemFqcn ?? $phpType;
            $constructor->addComment("@param list<{$itemType}> \${$parameterName}");
        } elseif ($fixedScalar !== null) {
            $param = $constructor->addPromotedParameter($parameterName, $fixedScalar)->setType($phpType);
        } else {
            $param = $constructor->addPromotedParameter($parameterName, null)->setType($phpType)->setNullable(true);
        }

        $param->addAttribute(FhirProperty::class, $attributeArgs);
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
