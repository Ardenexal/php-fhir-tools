<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

/**
 * The CDA datatype hierarchy, as the definitions publish it.
 *
 * A CDA element may admit many datatypes — an observation's value admits 29 — and two questions have
 * to be answered before such an element can be generated: what single PHP type can hold all of them,
 * and in what order must a serializer test them. Neither is answerable from the class hierarchy or
 * from reflection, so both are computed here from the definitions' own `type`/`baseDefinition` links.
 *
 * Parentage is deliberately the *type-aware* link the caller supplies, not `baseDefinition` alone:
 * an AU profile and its core type can share a `baseDefinition`, so `baseDefinition` does not place
 * the real type-parent first (see the CDA parent-constructor footgun).
 *
 * @author Ardenexal
 */
final class CdaTypeHierarchy
{
    /**
     * Memoised self-first ancestor chains, keyed by canonical URL.
     *
     * @var array<string, list<string>>
     */
    private array $chains = [];

    /**
     * @param array<string, string>      $urlToName Canonical SD URL → the definition's published `name`.
     *                                              This is the only trustworthy source of a CDA type name:
     *                                              nine of the thirty datatypes an element can admit spell
     *                                              their URL and their name differently (`.../IVL-PQ` is
     *                                              named `IVL_PQ`, `.../RTO-PQ-PQ` is named `RTO_PQ_PQ`),
     *                                              so a name derived from the URL's last segment or from
     *                                              the generated class name is wrong for those.
     * @param array<string, string|null> $parentOf  Canonical SD URL → its type-parent's canonical URL.
     *                                              Null or absent means no parent, which is how the
     *                                              abstract roots present: their `baseDefinition` points
     *                                              outside the CDA hierarchy or is missing entirely.
     */
    public function __construct(
        private readonly array $urlToName,
        private readonly array $parentOf,
    ) {
    }

    /**
     * The definition's published name, or '' when this URL is not a known definition.
     *
     * @param string $url Canonical URL of the definition whose published name is wanted
     *
     * @return string The published name, or an empty string when the URL is unknown
     */
    public function typeName(string $url): string
    {
        return $this->urlToName[$url] ?? '';
    }

    /**
     * Self-first ancestor chain: `[$url, parent, grandparent, …]`.
     *
     * Stops on an unknown URL and on a repeat, so a cyclic `type`/`baseDefinition` pair in a
     * published package cannot hang generation.
     *
     * @param string $url Canonical URL to start the walk from
     *
     * @return list<string> The type itself followed by each ancestor, nearest first; empty when unknown
     */
    public function ancestors(string $url): array
    {
        if (isset($this->chains[$url])) {
            return $this->chains[$url];
        }

        $chain   = [];
        $seen    = [];
        $current = $url;
        while ($current !== '' && isset($this->urlToName[$current]) && !isset($seen[$current])) {
            $seen[$current] = true;
            $chain[]        = $current;
            $current        = $this->parentOf[$current] ?? '';
        }

        return $this->chains[$url] = $chain;
    }

    /**
     * The nearest type every given URL derives from, or null when they share no ancestor.
     *
     * This is what an element's PHP type must be. Widening further would admit datatypes the
     * definition forbids: the effective-time and useable-period elements bottom out at `SXCM_TS`,
     * not at `ANY`, so typing them `ANY` would let a plain string through where only a timestamp
     * expression belongs.
     *
     * @param list<string> $urls Canonical URLs of every datatype the element admits
     *
     * @return string|null Canonical URL of the nearest shared ancestor, or null when they share none
     */
    public function leastCommonAncestor(array $urls): ?string
    {
        if ($urls === []) {
            return null;
        }

        $chains = [];
        foreach ($urls as $url) {
            $chain = $this->ancestors($url);
            if ($chain === []) {
                return null;
            }
            $chains[] = $chain;
        }

        // Walk the first member's chain from itself upwards; the first entry shared by every other
        // chain is the nearest common ancestor.
        foreach ($chains[0] as $candidate) {
            foreach ($chains as $chain) {
                if (!in_array($candidate, $chain, true)) {
                    continue 2;
                }
            }

            return $candidate;
        }

        return null;
    }

    /**
     * Reorder so that no URL precedes one of its own descendants.
     *
     * Serializers pick a variant by walking the list and taking the first `instanceof` match, so a
     * supertype listed before its subtype steals the match and the value serializes under the wrong
     * type — silently, with structurally valid output (see the choice-variant-ordering footgun).
     *
     * The definitions themselves are ordered the wrong way round: an observation's value lists `CD`
     * before `CE`, `CO`, `CS` and `CV`, `TS` before its four interval types, and `INT` before
     * `INT_POS` — 17 such pairs in that one element. So the published order cannot be emitted as-is.
     *
     * The sort is stable: most members are unrelated siblings whose relative order is arbitrary, and
     * keeping it fixed stops the generated output churning between runs.
     *
     * @param list<string> $urls Canonical URLs to reorder
     *
     * @return list<string> The same URLs, with every type placed before any of its own ancestors
     */
    public function sortDescendantFirst(array $urls): array
    {
        $sorted = $urls;

        // Insertion sort: stable, and the comparison is a partial order (most pairs are unrelated),
        // which a comparison sort is not guaranteed to handle consistently.
        for ($i = 1; $i < count($sorted); ++$i) {
            $candidate = $sorted[$i];
            $target    = $i;
            // Scan the whole left side and remember the LEFTMOST ancestor found. Stopping at the
            // first non-ancestor would be wrong: unrelated siblings sit between related pairs, so
            // in `[CD, ST, CE]` the scan must look past `ST` to discover that `CE` derives from
            // `CD`. Most of the thirty members of a real slot are unrelated, so that gap is the
            // normal case rather than an edge case.
            for ($j = $i - 1; $j >= 0; --$j) {
                if ($this->isDescendantOf($candidate, $sorted[$j])) {
                    $target = $j;
                }
            }
            if ($target !== $i) {
                array_splice($sorted, $i, 1);
                array_splice($sorted, $target, 0, [$candidate]);
            }
        }

        return $sorted;
    }

    /**
     * True when `$url` derives from `$possibleAncestor`, and they are not the same type.
     *
     * @param string $url              Canonical URL of the type that might be the descendant
     * @param string $possibleAncestor Canonical URL of the type that might be the ancestor
     *
     * @return bool True only for a strict descendant; a type is not its own descendant
     */
    public function isDescendantOf(string $url, string $possibleAncestor): bool
    {
        if ($url === $possibleAncestor) {
            return false;
        }

        return in_array($possibleAncestor, $this->ancestors($url), true);
    }
}
