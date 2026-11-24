<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports the relative references inside a Bundle that the Bundle cannot resolve.
 *
 * In every Bundle, the root and any nested one, a relative reference must have the shape
 * `[type]/[id]` and its entry must have a `fullUrl` to resolve against. In a document or a message the
 * reference must then resolve to exactly one entry: two matches are ambiguous, a near miss is described
 * by the fullUrl rules below, and no match at all is plain absence.
 *
 * The rest of this docblock is about the near miss.
 *
 * A Bundle entry's `fullUrl` is what a reference inside another entry resolves *against*. There is no
 * base URL to resolve against when a document identifies its entries by `urn:uuid:`, by a relative
 * `fullUrl`, or by one whose id disagrees with the resource's own. A relative `Type/id` reference then
 * matches nothing even though a resource of exactly that type and id sits in the bundle. That is a
 * broken link, and it validated clean here while the HL7 Java reference validator reported one error
 * per reference.
 *
 * ## The rule, established from the corpus rather than from the reference validator's wording
 *
 * `vendor/fhir/fhir-test-cases/validator/` ships four fixtures that vary only what this rule turns on.
 * The outcome column below is read from each one's vendored reference outcome under `outcomes/java/`,
 * which is the oracle this project is measured against:
 *
 * | Fixture | Source entry `fullUrl` | Source `id` | Target `id` | Reference outcome |
 * |---|---|---|---|---|
 * | `relative_reference_to_fullUrl.id_in_Composition` | `http://zrbj.eu/x/Composition/666` | yes | no | resolves |
 * | `relative_reference_to_fullUrl.id_in_target_resource` | same | no | yes | fails |
 * | `relative_reference_to_fullUrl.no_ids.PROBLEM` | same | no | no | fails, no candidate to name |
 * | `relative_reference_to_TYPE_ID.all_fullUrl_UUID` | `urn:uuid:10a67a86-…` | n/a | yes | fails |
 *
 * **Do not take the outcome from the fixture titles.** Two of them disagree with their own reference
 * outcome: `id_in_target_resource` and `all_fullUrl_UUID` both end `-> OK`, and the reference validator
 * reports an error on both. Only `id_in_Composition` is corroborated by its title. A reader who trusts
 * the titles will read this class as having two false positives.
 *
 * The target's own id is irrelevant; the **source** entry's is decisive. A base URL can be derived
 * only when the source `fullUrl` reads as `[base]/[type]/[id]` with `[id]` equal to that entry's own
 * resource id. Matching is then literal equality against each entry's `fullUrl`, never a type-and-id
 * lookup, which is the whole point of the rule and the reason a naive index reports nothing here.
 *
 * The reference validator shows its arithmetic on the fourth fixture: it reports the full target URL
 * as `urn:uuid:666`, built from a `urn:` base that yields nothing, which is why every `urn:uuid`
 * document reports on every relative reference it holds.
 *
 * ## Why this is not a Symfony constraint
 *
 * Same reason as {@see BundleEntryFullUrlChecker}: the rule is not a property of any one element. It
 * needs every entry's `fullUrl` in hand before it can judge a reference several entries away, so it
 * walks the tree itself and is independent of what the generator emits.
 *
 * ## Scoping
 *
 * A reference with no same-type-and-id candidate is plain absence, `Can't find 'X' in the bundle`
 * without the fullUrl clause. It is an error only from the Composition, as the reference validator
 * reports it; from any other entry it is information, because the target may sit on a server.
 *
 * A reference's own entry's `contained` is not searched, and `#` fragments are not judged here: they
 * resolve against `contained`, which {@see ContainedReferenceChecker} owns. A Bundle reached through
 * `contained` (`bundle-ea-testcase` holds one in a MeasureReport) is checked against its own entries,
 * never the outer Bundle's, which is how this rule would otherwise manufacture `ABOVE` cases.
 *
 * The bundle is indexed once per validation, not once per reference. Walking every entry for every
 * reference is quadratic in entry count, and `bundle-duplicate-ids-not` is a 39-entry document.
 *
 * Which entries a reference resolves to is decided by {@see BundleEntryIndex}, which
 * {@see BundleReachabilityChecker} shares, so both rules read the bundle the same way.
 *
 * @phpstan-import-type IndexedEntry from BundleEntryIndex
 */
final class BundleReferenceResolutionChecker
{
    private readonly BundleEntryReader $reader;

    public function __construct(
        FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
        $this->reader = new BundleEntryReader($models, $attributes);
    }

    /** Identifies the root resource we apply to, read from the class attribute. */
    private const string BUNDLE = 'Bundle';

    /** The one entry whose missing reference targets are errors rather than information. */
    private const string COMPOSITION = 'Composition';

    /** Identifies a reference node, by FHIR type rather than by the presence of a property name. */
    private const string REFERENCE = 'Reference';

    /**
     * The only two bundle kinds whose internal references the reference validator resolves.
     *
     * A `document` is a closed, self-contained set and a `message` is its equivalent for messaging, so
     * a reference leading outside one is a broken link. Every other kind (`collection`, `searchset`,
     * `transaction`) is an open envelope whose entries may legitimately reference resources that are
     * simply elsewhere, and the reference validator reports nothing on them. Gating on this is what
     * keeps `bnd-ambiguous-refs`, `bundle-duplicate-id` and both `ref-policy` bundles silent: every one
     * is a `collection`, and reporting on them manufactured four of this rule's first seven `ABOVE`
     * cases.
     *
     * That measured silence is the whole evidence for the gate. `bdl-11` and `bdl-12` are the only
     * invariants that single out these two kinds, constraining the first entry of a document and of a
     * message, which is suggestive but says nothing about references and is not what the gate rests on.
     */
    private const array RESOLVING_BUNDLE_TYPES = ['document', 'message'];

    /** An absolute reference opens with a scheme (`http:`, `urn:`), as in {@see BundleEntryFullUrlChecker}. */
    private const string SCHEME = '/\A[A-Za-z][A-Za-z0-9+.\-]*:/';

    /**
     * The loosest shape a relative reference may take: `[type]/[id]`, optionally `/_history/[version]`.
     *
     * Anything else that is neither absolute nor a fragment is reported as malformed. Deliberately
     * loose, because only the bare-id form has corpus evidence (`denom-EXM104` in `bundle-ea-testcase`):
     * a reference with an odd character in its id is a different finding and is not guessed at here.
     */
    private const string RELATIVE_SHAPE = '~\A[A-Za-z]+/[^/]+(?:/_history/[^/]+)?\z~';

    /**
     * Walk every Bundle in the resource, the root and any nested one, and report each reference its
     * entries hold that the Bundle cannot resolve.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $bundles = [];
        $visited = [];
        $this->collectBundles($resource, '', $visited, $bundles);

        $violations = [];

        foreach ($bundles as $path => $bundle) {
            foreach ($this->checkBundle($bundle, (string) $path) as $violation) {
                $violations[] = $violation;
            }
        }

        return $violations;
    }

    /**
     * Find every Bundle in the tree, keyed by its path, including those held in `contained`.
     *
     * `bundle-ea-testcase` keeps a collection Bundle inside a MeasureReport's `contained`, and the
     * reference validator checks the references in that Bundle against that Bundle's own entries.
     *
     * @param array<int, true>      $visited spl_object_id keys of already-visited objects (cycle guard)
     * @param array<string, object> $bundles Bundles found so far, keyed by path
     */
    private function collectBundles(object $node, string $path, array &$visited, array &$bundles): void
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return;
        }

        $visited[$id] = true;

        if ($this->reader->resourceType($node) === self::BUNDLE) {
            $bundles[$path] = $node;
        }

        // A primitive holds only its value, id and extensions, and no extension value is a resource.
        if ($this->reader->isPrimitive($node)) {
            return;
        }

        foreach ($this->reader->children($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                $this->collectBundles($value, $subPath, $visited, $bundles);
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $this->collectBundles($item, $subPath . '[' . $i . ']', $visited, $bundles);
                    }
                }
            }
        }
    }

    /**
     * Apply the rules to the references one Bundle's entries hold.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkBundle(object $bundle, string $bundlePath): array
    {
        $index = $this->reader->index($bundle);

        if ($index->entries === []) {
            return [];
        }

        $resolves   = in_array($this->reader->readString($bundle, 'type'), self::RESOLVING_BUNDLE_TYPES, true);
        $prefix     = $bundlePath === '' ? '' : $bundlePath . '.';
        $violations = [];

        foreach ($index->entries as $position => $entry) {
            if ($entry['resource'] === null) {
                continue;
            }

            $visited = [];
            $found   = $this->references($entry['resource'], $prefix . 'entry[' . $position . '].resource', $visited);

            foreach ($found as $path => $reference) {
                $violation = $this->checkReference($reference, (string) $path, $position, $index, $resolves);

                if ($violation !== null) {
                    $violations[] = $violation;
                }
            }
        }

        return $violations;
    }

    /**
     * Collect every `Reference` node beneath a resource, keyed by its path.
     *
     * @param array<int, true>      $visited spl_object_id keys of already-visited objects (cycle guard)
     * @param array<string, object> $found   references collected so far, carried down the recursion
     *
     * @return array<string, object>
     */
    private function references(object $node, string $path, array &$visited, array $found = []): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return $found;
        }

        $visited[$id] = true;

        // A Bundle below the entry resolves its own entries' references; see collectBundles(). An entry
        // whose resource *is* a Bundle holds nothing but those entries, so the same applies to it.
        if ($this->reader->resourceType($node) === self::BUNDLE) {
            return $found;
        }

        if ($this->reader->typeName($node) === self::REFERENCE) {
            $found[$path] = $node;

            return $found;
        }

        foreach ($this->reader->children($node) as $name => $value) {
            // Contained resources resolve by their own rules, never against the bundle's entries.
            if ($name === 'contained') {
                continue;
            }

            $subPath = $path . '.' . $name;

            if (is_object($value)) {
                $found = $this->references($value, $subPath, $visited, $found);
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $found = $this->references($item, $subPath . '[' . $i . ']', $visited, $found);
                    }
                }
            }
        }

        return $found;
    }

    /**
     * Apply the rules to one reference.
     *
     * Two shape rules hold in every Bundle; resolution itself only in a document or a message, see
     * {@see RESOLVING_BUNDLE_TYPES}. Absolute references, `#` fragments and conditional references are
     * left alone: none of them resolves against an entry's base, and each has its own rule elsewhere.
     *
     * @param int $source the position of the entry that holds the reference
     */
    private function checkReference(object $reference, string $path, int $source, BundleEntryIndex $index, bool $resolves): ?FHIRValidationViolation
    {
        $value = $this->reader->readString($reference, 'reference');

        if ($value === null || str_starts_with($value, '#') || str_contains($value, '?') || preg_match(self::SCHEME, $value) === 1) {
            return null;
        }

        if (preg_match(self::RELATIVE_SHAPE, $value) !== 1) {
            return $this->violation($path, sprintf('Relative URLs must be of the format [ResourceName]/[id]. Encountered %s', $value));
        }

        $entry = $index->entries[$source];

        if (!$entry['hasFullUrl']) {
            return $this->violation($path, 'Relative Reference appears inside Bundle whose entry is missing a fullUrl');
        }

        $parts = $resolves ? BundleEntryIndex::parseRelative($value) : null;

        if ($parts === null) {
            return null;
        }

        $matches = count($index->resolveRelative($parts['type'], $parts['id'], $parts['version'], $source));

        if ($matches === 1) {
            return null;
        }

        if ($matches > 1) {
            return $this->violation($path, sprintf("Found %d matches for '%s' in the bundle", $matches, $value));
        }

        $candidates = $index->fullUrlsOfTypeAndId($parts['type'], $parts['id']);

        // Plain absence is an error only from the Composition, whose references are what a document
        // is made of. From any other entry the reference validator reports it as information: a
        // message's Observation may point at a Patient held on a server (`R4.message`,
        // `R3.bundle-ref`), while `R3.bundle.document` reports every Composition reference it misses.
        if ($candidates === []) {
            return $this->violation(
                $path,
                sprintf("Can't find '%s' in the bundle", $value),
                $entry['type'] === self::COMPOSITION ? 'error' : 'information',
            );
        }

        return $this->violation($path, $this->message($value, $candidates, $entry['fullUrl']));
    }

    private function violation(string $path, string $message, string $severity = 'error'): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: $severity,
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * This wording is our own, because the reference validator's sentence carries a context label we
     * cannot reproduce: `Section Entry` and `MessageHeader Data` are its own names for spec slots. It
     * keeps the phrase `fullUrl based rules`, which is what commits either validator to this rule rather
     * than to plain absence or to ambiguity, and which is how the two are paired.
     *
     * @param list<string> $candidates
     */
    private function message(string $reference, array $candidates, ?string $sourceFullUrl): string
    {
        $source = $sourceFullUrl ?? '(none)';

        if (count($candidates) === 1) {
            return sprintf(
                "Can't find '%s' in the bundle. A resource with the same type and id is present at fullUrl '%s', but the fullUrl based rules around matching relative references do not match it against the source fullUrl '%s'",
                $reference,
                $candidates[0],
                $source,
            );
        }

        return sprintf(
            "Can't find '%s' in the bundle. %d resources with the same type and id are present at fullUrls '%s', but the fullUrl based rules around matching relative references match none of them against the source fullUrl '%s'",
            $reference,
            count($candidates),
            implode(',', $candidates),
            $source,
        );
    }
}
