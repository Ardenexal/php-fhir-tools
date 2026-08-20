<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;

/**
 * Orders `value[x]` choice variants so a subtype is always declared before its supertype.
 *
 * `AbstractFHIRNormalizer::resolveChoiceVariant` matches a runtime value against variants **in
 * declaration order**, using `instanceof` for objects. The generated primitive wrappers form real
 * inheritance chains (`CodePrimitive extends StringPrimitive`, `UrlPrimitive extends UriPrimitive`,
 * `PositiveIntPrimitive extends IntegerPrimitive`, …), so a supertype listed first captures its
 * subtype's values and emits the wrong `value[x]` key — silently, with a structurally valid result.
 *
 * The trap this class exists to prevent is specifically **alphabetical** ordering, which looks like
 * safe canonicalisation and is correct for most pairs by luck (`code` < `string`, `canonical` <
 * `uri`). It is wrong for at least one real pair: `{uri, url}` sorts to `uri, url`, but
 * `UrlPrimitive extends UriPrimitive`, so a `UrlPrimitive` would emit as `valueUri`.
 * {@see AllowedTypeReader::read()} deliberately returns a *sorted* list — that sort exists so R4 and
 * R5 compare equal, and it must never be used as the emitted variant order.
 *
 * ## Depth, not a comparator
 *
 * "Is A more specific than B" is a **partial** order: in `{uri, url, canonical}`, both `url` and
 * `canonical` derive from `uri` but are unrelated to each other. Handing a non-transitive comparator
 * to `usort()` produces implementation-defined output. So this class ranks each type by its
 * **specialization depth** — the number of `baseDefinition` hops to the root — and sorts descending.
 * Depth is a total order, a subtype's depth always exceeds its supertype's, and incomparable types
 * fall back to an alphabetical tie-break so that regeneration stays byte-deterministic.
 *
 * ## Depth comes from the specification, not a table
 *
 * Each primitive's StructureDefinition carries `baseDefinition`, and that chain mirrors the PHP
 * inheritance exactly (`code → string`, `url → uri`, `positiveInt → integer`). Reading it from
 * {@see BuilderContextInterface::getDefinition()} means the ordering cannot drift from the models the
 * way a hand-maintained table would. `BuilderContext::sortDefinitionsByInheritance` already derives
 * generation order from `baseDefinition` the same way.
 *
 * @author Ardenexal
 */
final class VariantOrderer
{
    private const string STRUCTURE_DEFINITION_URL = 'http://hl7.org/fhir/StructureDefinition/';

    /**
     * Guards against a cyclic `baseDefinition` chain in a malformed package. The real chains are at
     * most 3 deep; anything approaching this is corrupt data, not a deep hierarchy.
     */
    private const int MAX_CHAIN_DEPTH = 32;

    /**
     * Order FHIR type codes subtype-first.
     *
     * @param list<string> $typeCodes Type codes, in any order (typically `AllowedTypeReader`'s
     *                                alphabetically sorted output)
     *
     * @return list<string> The same codes, ordered so no supertype precedes one of its subtypes
     */
    public function order(array $typeCodes, BuilderContextInterface $context): array
    {
        $ordered = array_values(array_unique($typeCodes));

        usort($ordered, function(string $a, string $b) use ($context): int {
            $byDepth = $this->depthOf($b, $context) <=> $this->depthOf($a, $context);

            // Alphabetical only breaks ties between genuinely incomparable types (`url` vs
            // `canonical`, both depth 2). It never decides a subtype/supertype pair, because their
            // depths always differ — which is the whole reason depth is the primary key.
            return $byDepth !== 0 ? $byDepth : strcmp($a, $b);
        });

        return $ordered;
    }

    /**
     * How many `baseDefinition` hops separate a type from the root of its specialization chain.
     *
     * An unknown type code yields depth 0, which sorts it last. That is the safe direction: a type
     * the context cannot resolve is never claimed to be more specific than one it can, so it cannot
     * steal a match from a known subtype.
     */
    public function depthOf(string $typeCode, BuilderContextInterface $context): int
    {
        $depth = 0;
        $url   = self::STRUCTURE_DEFINITION_URL . $typeCode;
        $seen  = [];

        while ($depth < self::MAX_CHAIN_DEPTH) {
            if (isset($seen[$url])) {
                break;
            }

            $seen[$url]  = true;
            $definition  = $context->getDefinition($url);
            $base        = $definition['baseDefinition'] ?? null;

            // Both `specialization` and `constraint` extend the *PHP* hierarchy, which is what this
            // ranking is about.
            //
            // This guard previously admitted only `specialization`, on the premise that "a
            // `constraint` derivation is a profile of the same type — it adds no PHP subclass, so
            // counting it would invent depth". The shipped models contradict that:
            // `Models/src/R4/Profile/SimpleQuantityProfile.php` is
            // `class SimpleQuantityProfile extends Quantity`, generated by `buildProfiles` from a
            // constraint-derived StructureDefinition, and `MoneyQuantityProfile` likewise.
            //
            // Excluding them ranked `SimpleQuantity` at 0 and `Quantity` at 1, so the SUPERTYPE was
            // declared first — and since variants are matched by `instanceof` in declaration order, a
            // `SimpleQuantityProfile` value matched `Quantity` and serialised under `valueQuantity`.
            // That is precisely the failure this class exists to prevent, and it failed silently.
            //
            // What the depth actually counts is "how many generated classes are between this one and
            // the root", and a profile class is one of them. `derivation: null` (or anything else)
            // still stops the walk, because nothing then guarantees a subclass was emitted.
            $derivation = $definition['derivation'] ?? null;

            if (!is_string($base) || !in_array($derivation, ['specialization', 'constraint'], true)) {
                break;
            }

            ++$depth;
            $url = $base;
        }

        return $depth;
    }
}
