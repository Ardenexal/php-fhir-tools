<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

/**
 * Turns a logical model's anonymous nested element groups into real StructureDefinitions.
 *
 * CDA R2 defines wrapper classes between an act and its children — the section-level component,
 * the nested-section component, `consumable`, `location`, and so on. `hl7.cda.uv.core` publishes
 * only *some* of them as named types (`Component`, `ComponentOf`, `OrganizerComponent`) and
 * declares the rest inline, as nested element paths inside the parent's snapshot:
 *
 *     StructuredBody.component                        type = .../InfrastructureRoot
 *     StructuredBody.component.typeCode               representation = xmlAttr, fixed COMP
 *     StructuredBody.component.contextConductionInd   representation = xmlAttr
 *     StructuredBody.component.section        min 1   type = .../Section
 *
 * {@see LogicalModelGenerator::collectOwnParameters()} takes direct children only, because for a
 * datatype-typed element the deeper paths are refinements of something already typed (`II` fully
 * describes `Component.typeId.root`). For these groups they are the *only* carrier of the content
 * model, so the wrapper collapsed to its declared base type and a `Section` placed in
 * `StructuredBody::$component` serialized under the property name with no `<section>` element.
 *
 * This class closes that gap without touching the emitter: each group becomes a synthetic
 * `kind: logical` SD with the nested subtree re-rooted onto it, and the wrapping element's `type`
 * is repointed at the synthetic URL. Everything downstream — class naming, the `parentOf` chain,
 * `parent::__construct()` forwarding, namespace routing, file output — then treats a wrapper as an
 * ordinary logical model.
 *
 * Two element shapes are deliberately left alone:
 *
 * - **Flattened datatype content.** CDA inlines a datatype's own elements into the parent snapshot
 *   (`Component.typeId.*` under an `II`-typed element). Detected by asking whether the declared
 *   type's SD already declares those child names, rather than by listing datatype URLs — the
 *   list-based form silently missed the six `Base`-typed groups when this was first scoped.
 * - **Transparent choice groups** (`AD.item`, `EN.item`, `ON.item`, `PN.item`, `TN.item`), which
 *   carry the `xml-choice-group` tooling extension and must emit their children with *no* wrapper
 *   element at all. Those belong to the choice-group mechanism, not here.
 */
final class NestedWrapperSynthesizer
{
    /**
     * Tooling extension marking a transparent (wrapper-less) choice group.
     */
    private const string XML_CHOICE_GROUP = 'xml-choice-group';

    /**
     * Suffix joining a parent SD id to a wrapping element name to form the synthetic canonical URL
     * (e.g. `.../StructureDefinition/StructuredBody-component`). A hyphen rather than a fragment
     * keeps the URL's last segment a plain identifier, so both class-name derivation paths — the
     * `name`-driven one for core and the id-driven one for AU additions — yield the same
     * `StructuredBodyComponent` / `AuStructuredBodyComponent`.
     */
    private const string URL_JOIN = '-';

    /**
     * Synthetic wrapper URL → the URL of the model it was lifted out of, recorded by
     * {@see expand()} and read back via {@see wrapperOwners()}.
     *
     * A wrapper's `baseDefinition` is whatever its element declared, which for
     * `AssignedEntity.sdtcPatient` is the FHIR `Base` type — no CDA ancestry at all. Routing that
     * decides datatype-vs-clinical output by walking the parent chain therefore cannot place such a
     * wrapper, so it is placed with the model that owns it instead.
     *
     * @var array<string, string>
     */
    private array $owners = [];

    /**
     * Add a synthetic SD for every anonymous nested wrapper group, and repoint each wrapping
     * element's `type` at the class that now describes it.
     *
     * Only the models passed in are scanned, so a wrapper nested inside another wrapper is not
     * synthesized. `hl7.cda.uv.core#2.0.2-sd` has no such case — every wrapper's children are
     * leaves or named types — and a second pass should be added if one ever appears.
     *
     * @param array<string, array<string, mixed>> $definitions canonical URL → StructureDefinition
     *
     * @return array<string, array<string, mixed>> the input definitions, with wrapping elements
     *                                             retyped and synthetic wrapper SDs appended
     */
    public function expand(array $definitions): array
    {
        $synthetic = [];

        foreach ($definitions as $url => $definition) {
            $elements = $definition['snapshot']['element'] ?? [];
            if (!is_array($elements)) {
                continue;
            }

            $paths = [];
            foreach ($elements as $element) {
                if (is_array($element) && isset($element['path'])) {
                    $paths[] = (string) $element['path'];
                }
            }

            foreach ($elements as $index => $element) {
                if (!is_array($element)) {
                    continue;
                }
                $path = (string) ($element['path'] ?? '');
                if (!$this->isWrapperElement($element, $path, $paths, $definitions)) {
                    continue;
                }

                $wrapperUrl = $this->syntheticUrl($url, $path);
                if (isset($definitions[$wrapperUrl]) || isset($synthetic[$wrapperUrl])) {
                    // A real SD already owns this URL, or two elements in one SD reduced to the
                    // same name. Leave the element as it was rather than shadowing a published
                    // type; the collision check in generation reports it.
                    continue;
                }

                $synthetic[$wrapperUrl]    = $this->buildDefinition($definition, $element, $path, $wrapperUrl);
                $this->owners[$wrapperUrl] = $url;

                // Repoint the wrapping element at its new class so the parent's property types at
                // the wrapper instead of at the generic base.
                $definitions[$url]['snapshot']['element'][$index]['type'] = [['code' => $wrapperUrl]];
            }
        }

        return $definitions + $synthetic;
    }

    /**
     * Synthetic wrapper URL → the URL of the model it was lifted out of, for callers that route
     * output by ancestry. Populated by {@see expand()}; empty before it runs.
     *
     * @return array<string, string>
     */
    public function wrapperOwners(): array
    {
        return $this->owners;
    }

    /**
     * True when an element declares nested children that nothing else already describes.
     *
     * @param array<string, mixed>                $element
     * @param list<string>                        $paths       every path in the element's own snapshot
     * @param array<string, array<string, mixed>> $definitions
     */
    private function isWrapperElement(array $element, string $path, array $paths, array $definitions): bool
    {
        // The SD root has no dot and is the class itself, never a wrapper property.
        if ($path === '' || !str_contains($path, '.')) {
            return false;
        }

        $children = $this->directChildNames($path, $paths);
        if ($children === []) {
            return false;
        }

        if ($this->hasExtension($element, self::XML_CHOICE_GROUP)) {
            return false;
        }

        $types    = $element['type'] ?? [];
        $typeCode = (is_array($types) && isset($types[0]) && is_array($types[0]))
            ? (string) ($types[0]['code'] ?? '')
            : '';

        return !$this->typeDeclares($typeCode, $children, $definitions);
    }

    /**
     * Local names of an element's immediate children within the same snapshot.
     *
     * @param list<string> $paths
     *
     * @return list<string>
     */
    private function directChildNames(string $path, array $paths): array
    {
        $depth  = substr_count($path, '.') + 1;
        $prefix = $path . '.';
        $names  = [];
        foreach ($paths as $candidate) {
            if (str_starts_with($candidate, $prefix) && substr_count($candidate, '.') === $depth) {
                $names[substr($candidate, (int) strrpos($candidate, '.') + 1)] = true;
            }
        }

        return array_keys($names);
    }

    /**
     * True when the element's declared type already declares all of these child names — i.e. the
     * nested paths are CDA's flattened copy of a datatype's own content, not a new content model.
     *
     * @param list<string>                        $children
     * @param array<string, array<string, mixed>> $definitions
     */
    private function typeDeclares(string $typeCode, array $children, array $definitions): bool
    {
        if ($typeCode === '' || !isset($definitions[$typeCode])) {
            return false;
        }

        $declared = [];
        $elements = $definitions[$typeCode]['snapshot']['element'] ?? [];
        if (!is_array($elements)) {
            return false;
        }
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }
            $path = (string) ($element['path'] ?? '');
            if (!str_contains($path, '.')) {
                continue;
            }
            $remainder           = substr($path, (int) strpos($path, '.') + 1);
            $first               = explode('.', $remainder)[0];
            $declared[$first]    = true;
        }

        foreach ($children as $child) {
            if (!isset($declared[$child])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Canonical URL for a wrapper: the parent SD's URL with the wrapping element's dotted tail
     * appended. Nested tails keep every segment, so a wrapper inside a wrapper stays unique.
     */
    private function syntheticUrl(string $parentUrl, string $path): string
    {
        $tail = substr($path, (int) strpos($path, '.') + 1);

        return $parentUrl . self::URL_JOIN . str_replace('.', self::URL_JOIN, $tail);
    }

    /**
     * Build the synthetic StructureDefinition for one wrapper group.
     *
     * The wrapping element becomes the SD root, its descendants are re-rooted onto it, and the
     * element's own declared type becomes the new SD's `baseDefinition` — so a wrapper typed at
     * `InfrastructureRoot` extends the generated `InfrastructureRoot` class and inherits
     * `nullFlavor`/`realmCode`/`typeId`/`templateId` through the normal parent chain rather than
     * re-declaring them.
     *
     * @param array<string, mixed> $parent  the SD the group was found in
     * @param array<string, mixed> $element the wrapping element
     *
     * @return array<string, mixed>
     */
    private function buildDefinition(array $parent, array $element, string $path, string $wrapperUrl): array
    {
        $className = $this->className($parent, $path);
        $elements  = $parent['snapshot']['element'] ?? [];

        $reRooted = [];
        if (is_array($elements)) {
            foreach ($elements as $candidate) {
                if (!is_array($candidate)) {
                    continue;
                }
                $candidatePath = (string) ($candidate['path'] ?? '');
                if ($candidatePath !== $path && !str_starts_with($candidatePath, $path . '.')) {
                    continue;
                }
                $candidate['path'] = $className . substr($candidatePath, strlen($path));
                if (isset($candidate['id'])) {
                    $candidate['id'] = $candidate['path'];
                }
                $reRooted[] = $candidate;
            }
        }

        $types          = $element['type'] ?? [];
        $baseDefinition = (is_array($types) && isset($types[0]) && is_array($types[0]))
            ? (string) ($types[0]['code'] ?? '')
            : '';

        $definition = [
            'resourceType' => 'StructureDefinition',
            'id'           => $className,
            'url'          => $wrapperUrl,
            'name'         => $className,
            'status'       => (string) ($parent['status'] ?? 'active'),
            'fhirVersion'  => (string) ($parent['fhirVersion'] ?? '5.0.0'),
            'kind'         => 'logical',
            'abstract'     => false,
            'derivation'   => 'specialization',
            'type'         => $wrapperUrl,
            'snapshot'     => ['element' => $reRooted],
        ];

        if ($baseDefinition !== '') {
            $definition['baseDefinition'] = $baseDefinition;
        }

        // Carry the parent SD's own xml-namespace declaration so the namespace-inheritance walk
        // resolves the wrapper to the same namespace as the model it came from, instead of
        // falling back through a base chain that may not declare one.
        $parentExtensions = $parent['extension'] ?? [];
        if (is_array($parentExtensions)) {
            foreach ($parentExtensions as $extension) {
                if (is_array($extension) && str_contains((string) ($extension['url'] ?? ''), 'xml-namespace')) {
                    $definition['extension'] = [$extension];
                    break;
                }
            }
        }

        return $definition;
    }

    /**
     * PHP-facing name for a wrapper class: the parent's name followed by the wrapping element's
     * path segments, each capitalised (`StructuredBody` + `component` → `StructuredBodyComponent`).
     * Derived from the parent's `name` so core wrappers read naturally; AU additions re-derive from
     * the synthetic URL id in {@see ClassNameResolver::logicalModelClassName()} and pick up the
     * `Au` prefix there.
     *
     * @param array<string, mixed> $parent
     */
    private function className(array $parent, string $path): string
    {
        $name = (string) ($parent['name'] ?? $parent['id'] ?? '');
        $tail = substr($path, (int) strpos($path, '.') + 1);
        foreach (explode('.', $tail) as $segment) {
            $name .= ucfirst($segment);
        }

        return $name;
    }

    /**
     * @param array<string, mixed> $element
     */
    private function hasExtension(array $element, string $needle): bool
    {
        $extensions = $element['extension'] ?? [];
        if (!is_array($extensions)) {
            return false;
        }
        foreach ($extensions as $extension) {
            if (is_array($extension) && str_contains((string) ($extension['url'] ?? ''), $needle)) {
                return true;
            }
        }

        return false;
    }
}
