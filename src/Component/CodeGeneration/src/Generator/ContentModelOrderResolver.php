<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

/**
 * Works out each logical model's content model: every property name it can serialize, own and
 * inherited, in the order its StructureDefinition publishes them.
 *
 * The order cannot be recovered at runtime, which is why it is resolved here and recorded on the
 * generated class. `ReflectionClass::getProperties()` returns a class's own properties first and its
 * ancestors' last, so CDA's `InfrastructureRoot` contributions — `realmCode`, `typeId`, `templateId`,
 * which the content model puts *first* — reflect last on every act that inherits them. The class
 * hierarchy is no better: an AU child's own element can belong in the middle of its parent's
 * sequence, as `completionCode` does between `versionNumber` and `copyTime` on `ClinicalDocument`.
 *
 * A class's own snapshot is not the answer either, and this is the trap worth knowing about. The
 * snapshots are flattened, so a type's direct-child set is *usually* a superset of its parent's — but
 * an AU profile snapshot omits the `sdtc` extension elements its core parent declares. Deriving the
 * list from the child's snapshot alone loses `sdtcInFulfillmentOf1` on `au-SubstanceAdministration`,
 * `sdtcCategory` and `sdtcStatusCode` on `au-ClinicalDocument`, and seven elements on `au-Patient` —
 * 46 of 247 CDA classes when this was measured.
 *
 * So the parent's resolved list is the anchor, and each class's own elements are spliced into it at
 * the positions its own snapshot implies. That holds because the two sequences never disagree about
 * the elements they share: measured across all 247 CDA classes, zero contradictions.
 *
 * Names are compared case-sensitively throughout. CDA `Section` declares both an `ID` XML attribute
 * and an `id` element, which are distinct properties, and case-folding would merge them and lose one.
 *
 * @author Ardenexal
 */
final class ContentModelOrderResolver
{
    /**
     * Resolve the ordered property names for every definition.
     *
     * @param array<string, string|null>                $parentOf      definition URL → effective PHP parent URL
     * @param array<string, list<string>>               $snapshotOrder definition URL → its own snapshot's direct-child
     *                                                                 property names, in snapshot order
     * @param array<string, list<array<string, mixed>>> $ownParams     definition URL → the parameter descriptors this
     *                                                                 class declares itself, in snapshot order
     *
     * @return array<string, list<string>> definition URL → every serializable property name in published order
     */
    public function resolve(array $parentOf, array $snapshotOrder, array $ownParams): array
    {
        $resolved = [];
        foreach (array_keys($snapshotOrder) as $url) {
            $this->orderFor((string) $url, $parentOf, $snapshotOrder, $ownParams, $resolved);
        }

        return $resolved;
    }

    /**
     * @param array<string, string|null>                $parentOf
     * @param array<string, list<string>>               $snapshotOrder
     * @param array<string, list<array<string, mixed>>> $ownParams
     * @param string                                    $url           the definition whose order is wanted
     * @param array<string, list<string>>               $memo          resolved lists, also the cycle guard
     *
     * @return list<string> every serializable property name for this definition, in published order
     */
    private function orderFor(string $url, array $parentOf, array $snapshotOrder, array $ownParams, array &$memo): array
    {
        if (array_key_exists($url, $memo)) {
            return $memo[$url];
        }
        $memo[$url] = []; // cycle guard, matching the parameter-resolution walk

        $ownNames = $this->ownNames($ownParams[$url] ?? []);
        // `??` folds a null parent into the same empty-string case as a missing one: both mean "no
        // resolvable parent", which is what the root check below is asking.
        $parent = $parentOf[$url] ?? '';

        $parentOrder = ($parent !== '' && isset($snapshotOrder[$parent]))
            ? $this->orderFor($parent, $parentOf, $snapshotOrder, $ownParams, $memo)
            : [];

        // A root has nothing to merge into: its own snapshot IS its content model. Own names are
        // appended for the synthesized wrappers, whose re-rooted subtree can name an element the
        // depth-1 scan does not reach.
        if ($parentOrder === []) {
            return $memo[$url] = $this->unique([...($snapshotOrder[$url] ?? []), ...$ownNames]);
        }

        return $memo[$url] = $this->unique($this->splice($parentOrder, $ownNames, $snapshotOrder[$url] ?? []));
    }

    /**
     * Insert this class's own names into the parent's ordered list, each at the position its own
     * snapshot gives it: immediately before the first inherited name that follows it there.
     *
     * An own name the snapshot does not position goes last. That is the honest answer rather than a
     * guess — nothing in the definition says where it belongs.
     *
     * @param list<string> $parentOrder the parent's resolved order, used as the anchor
     * @param list<string> $ownNames    the names this class declares itself
     * @param list<string> $childOrder  this class's own snapshot order, which supplies the positions
     *
     * @return list<string> the parent's order with this class's own names inserted in place
     */
    private function splice(array $parentOrder, array $ownNames, array $childOrder): array
    {
        $position = array_flip($childOrder);

        $positioned = array_values(array_filter($ownNames, static fn (string $name): bool => isset($position[$name])));
        usort($positioned, static fn (string $left, string $right): int => $position[$left] <=> $position[$right]);

        $unpositioned = array_values(array_filter($ownNames, static fn (string $name): bool => !isset($position[$name])));

        $merged = [];
        foreach ($parentOrder as $inherited) {
            if (isset($position[$inherited])) {
                while ($positioned !== [] && $position[$positioned[0]] < $position[$inherited]) {
                    $merged[] = array_shift($positioned);
                }
            }
            $merged[] = $inherited;
        }

        return [...$merged, ...$positioned, ...$unpositioned];
    }

    /**
     * Read the property names out of a class's own parameter descriptors, preserving their order.
     *
     * @param list<array<string, mixed>> $descriptors this class's own parameter descriptors
     *
     * @return list<string> the names those descriptors declare, in the same order
     */
    private function ownNames(array $descriptors): array
    {
        $names = [];
        foreach ($descriptors as $descriptor) {
            $name = (string) ($descriptor['name'] ?? '');
            if ($name !== '') {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Keep the first occurrence of each name. `array_unique` compares as strings, so `ID` and `id`
     * stay distinct.
     *
     * @param list<string> $names possibly containing repeats
     *
     * @return list<string> the same names with later repeats dropped
     */
    private function unique(array $names): array
    {
        return array_values(array_unique($names));
    }
}
