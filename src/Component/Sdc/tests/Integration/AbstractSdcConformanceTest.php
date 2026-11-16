<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Reusable conformance oracle base for the SDC `$populate` and `$extract` operations.
 *
 * ## Why this exists (the comparison contract)
 *
 * Golden expected outputs are vendored **once** from a recognized SDC reference implementation and
 * frozen as the seeded baseline (see `tests/SOURCES.md`; the `questionnaire-conformance-seed-truth`
 * discipline). Conformance is then asserted by comparing the **deserialized model** structurally —
 * field by field — against that frozen baseline, NOT by byte-equality of serialized output.
 *
 * Byte-equality is the wrong contract: two spec-conformant serializers legitimately diverge on
 * element/answer/extension ordering, optional `text`/`display`, absent-vs-empty arrays,
 * server-generated `id`/`authored`/`lastUpdated`, and freshly-minted `urn:uuid:` reference values.
 * Asserting on raw bytes would fail on *correct* output. This base compares the semantic content and
 * deliberately ignores exactly that divergence surface.
 *
 * ## What is compared
 *
 * Both sides are reduced to a canonical structure by {@see canonicalize()} and then deep-compared:
 *
 *  - **$populate:** per-`linkId` item structure and each `answer.value[x]` are preserved and compared.
 *  - **$extract:** each entry's resource `resourceType`, its key properties, the Bundle `entry` count,
 *    and `request.method` are preserved and compared.
 *
 * ## The ignore-list (spec-legal divergence tolerated)
 *
 *  1. **Ordering** of every array (items, answers, extensions, Bundle entries) — lists are sorted by
 *     their canonical content, so order is not significant.
 *  2. **Optional `text` / `display`** — dropped everywhere (human-readable labels copied from the
 *     source Questionnaire / terminology, not semantic answer content).
 *  3. **Absent vs empty arrays** — an empty array is treated as an absent element.
 *  4. **Generated `id` / `authored` / `lastUpdated`** — server-minted, non-deterministic.
 *  5. **Referential `urn:uuid:` values** — normalised to positional tokens by {@see tokenizeUuids()}
 *     so that two documents using *different* random UUIDs still compare equal as long as their
 *     cross-reference topology matches.
 *
 * ## Usage
 *
 * Feature-plan conformance tests extend this base, serialize their operation's deserialized output
 * to a JSON array, load the frozen expected fixture, and call {@see assertSdcConformance()}:
 *
 * ```php
 * $actual   = json_decode($serializer->serialize($qrModel, FhirVersion::R4), true);
 * $expected = json_decode(file_get_contents($frozenFixturePath), true);
 * $this->assertSdcConformance($expected, $actual);
 * ```
 */
abstract class AbstractSdcConformanceTest extends TestCase
{
    /**
     * Element keys dropped from both sides before comparison.
     *
     * `id`/`authored`/`lastUpdated` are server-generated and non-deterministic; `text`/`display` are
     * optional human-readable labels, not semantic content. See the class docblock ignore-list.
     *
     * @var list<string>
     */
    protected const IGNORED_KEYS = ['id', 'text', 'display', 'authored', 'lastUpdated'];

    /**
     * Assert that an actual SDC operation output structurally conforms to the frozen expected baseline.
     *
     * Both operands are the decoded-JSON array form of the deserialized model. Neither is compared
     * byte-for-byte: both are reduced by {@see canonicalize()} (which applies the ignore-list) and the
     * canonical forms are deep-compared.
     *
     * @param array<string, mixed> $expected decoded frozen reference output
     * @param array<string, mixed> $actual   decoded toolkit output
     */
    protected function assertSdcConformance(array $expected, array $actual, string $message = ''): void
    {
        $canonicalExpected = $this->tokenizeUuids($this->canonicalize($expected));
        $canonicalActual   = $this->tokenizeUuids($this->canonicalize($actual));

        self::assertEquals(
            $canonicalExpected,
            $canonicalActual,
            $message !== '' ? $message : 'SDC output does not structurally conform to the reference baseline',
        );
    }

    /**
     * Reduce a value to its canonical comparison form (ignore-list applied, order-insensitive).
     *
     * Recursively: drops {@see IGNORED_KEYS}; drops elements whose canonical value is null or an
     * empty array (absent-vs-empty); ksort()s maps for deterministic key order; and sorts lists by
     * their canonical content so array ordering is not significant. `urn:uuid:` values are left
     * intact here and normalised in a second pass by {@see tokenizeUuids()}.
     */
    protected function canonicalize(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        if (array_is_list($value)) {
            $items = [];
            foreach ($value as $element) {
                $canonical = $this->canonicalize($element);
                if ($canonical === null || $canonical === []) {
                    continue; // absent-vs-empty: drop empties
                }
                $items[] = $canonical;
            }

            // Order-insensitive: sort by uuid-agnostic canonical content.
            usort($items, static function(mixed $a, mixed $b): int {
                $ka = self::sortKey($a);
                $kb = self::sortKey($b);

                return $ka <=> $kb;
            });

            return $items;
        }

        $map = [];
        foreach ($value as $key => $element) {
            if (in_array($key, static::IGNORED_KEYS, true)) {
                continue;
            }
            $canonical = $this->canonicalize($element);
            if ($canonical === null || $canonical === []) {
                continue; // absent-vs-empty: drop empties
            }
            $map[$key] = $canonical;
        }

        ksort($map);

        return $map;
    }

    /**
     * Stable, UUID-agnostic sort key for order-insensitive list comparison.
     *
     * All `urn:uuid:` values collapse to a single placeholder so that random UUIDs do not perturb
     * ordering; the actual UUID topology is compared after {@see tokenizeUuids()}.
     */
    private static function sortKey(mixed $value): string
    {
        $json = json_encode($value);
        $json = $json === false ? '' : $json;

        return (string) preg_replace('/urn:uuid:[0-9a-fA-F-]{36}/', 'urn:uuid:*', $json);
    }

    /**
     * Replace every distinct `urn:uuid:<uuid>` string with a positional token (`urn:uuid:#0`, `#1`, …).
     *
     * Tokens are assigned in the order UUIDs are first encountered in a depth-first walk of the
     * already-canonicalised (sorted) structure, and the mapping is applied consistently across the
     * whole document — so a resource's `fullUrl` and every `reference` pointing at it collapse to the
     * same token. This lets two documents that used different freshly-generated UUIDs compare equal
     * as long as their reference topology is identical.
     *
     * Limitation (acceptable for the current oracle): two entries identical except for their UUID are
     * order-ambiguous; real `$extract` fixtures in the `sdc-extract` plan validate this against a
     * fuller reference engine.
     */
    protected function tokenizeUuids(mixed $value): mixed
    {
        $map = [];
        $this->collectUuids($value, $map);

        if ($map === []) {
            return $value;
        }

        return $this->applyUuidMap($value, $map);
    }

    /**
     * @param array<string, string> $map uuid => token, populated in first-encounter order
     */
    private function collectUuids(mixed $value, array &$map): void
    {
        if (is_string($value)) {
            if (preg_match('/^urn:uuid:[0-9a-fA-F-]{36}$/', $value) === 1 && !isset($map[$value])) {
                $map[$value] = 'urn:uuid:#' . count($map);
            }

            return;
        }

        if (is_array($value)) {
            foreach ($value as $element) {
                $this->collectUuids($element, $map);
            }
        }
    }

    /**
     * @param array<string, string> $map uuid => positional token
     */
    private function applyUuidMap(mixed $value, array $map): mixed
    {
        if (is_string($value)) {
            return $map[$value] ?? $value;
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $key => $element) {
                $out[$key] = $this->applyUuidMap($element, $map);
            }

            return $out;
        }

        return $value;
    }
}
