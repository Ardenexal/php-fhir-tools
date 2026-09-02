<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports a relative `Reference.reference` that names a resource the Bundle holds, but which the
 * fullUrl matching rules refuse to resolve to it.
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
 * Only the **absent** direction is reported, and only when a same-type-and-id candidate exists. A
 * reference that resolves is silent, and so is one with no candidate at all. The reference validator
 * reports that second case too, but naming a resource that is genuinely not there is a different rule
 * with a different sentence, and it is recorded in the milestone backlog rather than guessed at here.
 *
 * `contained` is not descended into. Contained resources carry their own resolution rules (`ref-1`,
 * `dom-3`, and fragment lookup), none of which is this one, and no finding of this shape in the
 * vendored corpus sits inside a contained resource. Descending would risk resolving a nested bundle's
 * references against the outer bundle's index, which is how this rule would manufacture the `ABOVE`
 * cases it is written to avoid.
 *
 * The bundle is indexed once per validation, not once per reference. Walking every entry for every
 * reference is quadratic in entry count, and `bundle-duplicate-ids-not` is a 39-entry document.
 */
final class BundleReferenceResolutionChecker
{
    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /** Identifies the root resource we apply to, read from the class attribute. */
    private const string BUNDLE = 'Bundle';

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

    /**
     * A relative reference: `[Type]/[id]`, and deliberately not a version-specific one.
     *
     * Anything carrying a scheme (`http:`, `urn:`) or opening with `#` is absolute or a contained
     * fragment, and neither resolves through the rule this class implements.
     *
     * `Observation/ObservationExample/_history/1` is excluded because a versioned reference resolves
     * against an entry's `meta.versionId` rather than by matching its `fullUrl`, which is a separate
     * rule this class does not implement. `bundle-document-versioned-references-good` holds two such
     * references against two entries sharing one `fullUrl`, and the reference validator accepts both;
     * treating them as ordinary relative references reported both and put a clean case into `ABOVE`.
     */
    private const string RELATIVE_REFERENCE = '~\A(?<type>[A-Z][A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})\z~';

    /**
     * A `fullUrl` that yields a base: `[base]/[type]/[id]`, where `[id]` must be the entry's own.
     *
     * The base is allowed to be **empty**, which is not a technicality. `mni-patientOverview-bundle-example1`
     * identifies its entries relatively, as `Composition/1` and `Patient/1`, which
     * {@see BundleEntryFullUrlChecker} reports separately. Against an empty base `Patient/1` resolves to
     * `Patient/1`, which is exactly what that bundle holds, and the reference validator reports nothing
     * there. Its twin `…example1b` differs in one character, a Composition whose id is `1a` against a
     * fullUrl saying `1`, and the reference validator reports both of its references. A required
     * trailing slash collapses that distinction and reports both bundles.
     */
    private const string RESTFUL_FULL_URL = '~\A(?<base>(?:.*/)?)(?<type>[A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})\z~';

    /** @var array<class-string, string|null> FHIR resource type per class; reflection is not free */
    private array $resourceTypes = [];

    /** @var array<class-string, bool> whether the class is a `Reference` */
    private array $isReference = [];

    /**
     * Walk a document or message Bundle and report each relative reference the fullUrl rules refuse to
     * resolve to a same-type-and-id entry the bundle holds.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        if ($this->resourceType($resource) !== self::BUNDLE) {
            return [];
        }

        if (!in_array($this->readString($resource, 'type'), self::RESOLVING_BUNDLE_TYPES, true)) {
            return [];
        }

        $entries = $this->entries($resource);

        if ($entries === []) {
            return [];
        }

        $violations = [];

        foreach ($entries as $index => $entry) {
            if ($entry['resource'] === null) {
                continue;
            }

            $visited = [];

            foreach ($this->references($entry['resource'], 'entry[' . $index . '].resource', $visited) as $path => $reference) {
                $violation = $this->checkReference($reference, (string) $path, $entry, $entries);

                if ($violation !== null) {
                    $violations[] = $violation;
                }
            }
        }

        return $violations;
    }

    /**
     * Index the bundle's entries once: each entry's `fullUrl`, and the type and id of what it holds.
     *
     * @return array<int, array{fullUrl: string|null, resource: object|null, type: string|null, id: string|null}>
     */
    private function entries(object $bundle): array
    {
        // Deserializers bypass the constructor, so an absent field is uninitialized rather than null.
        $value = $this->readPublic($bundle, 'entry');

        if (!is_array($value)) {
            return [];
        }

        $entries = [];

        foreach ($value as $index => $entry) {
            if (!is_int($index) || !is_object($entry)) {
                continue;
            }

            $resource = $this->readObject($entry, 'resource');

            $entries[$index] = [
                'fullUrl'  => $this->readString($entry, 'fullUrl'),
                'resource' => $resource,
                'type'     => $resource === null ? null : $this->resourceType($resource),
                'id'       => $resource === null ? null : $this->readString($resource, 'id'),
            ];
        }

        return $entries;
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

        if ($this->isReference($node)) {
            $found[$path] = $node;

            return $found;
        }

        foreach ($this->models->publicPropertyNames($node) as $name) {
            if (!$this->models->isPropertyInitialized($node, $name)) {
                continue;
            }

            // Contained resources resolve by their own rules, never against the bundle's entries.
            if ($name === 'contained') {
                continue;
            }

            $value   = $this->models->readInitializedValue($node, $name);
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
     * Apply the rule to one reference.
     *
     * @param array{fullUrl: string|null, resource: object|null, type: string|null, id: string|null}             $source
     * @param array<int, array{fullUrl: string|null, resource: object|null, type: string|null, id: string|null}> $entries
     */
    private function checkReference(object $reference, string $path, array $source, array $entries): ?FHIRValidationViolation
    {
        $value = $this->readString($reference, 'reference');

        if ($value === null || preg_match(self::RELATIVE_REFERENCE, $value, $parts) !== 1) {
            return null;
        }

        $target = $this->targetUrl($value, $source);

        foreach ($entries as $entry) {
            if ($target !== null && $entry['fullUrl'] === $target) {
                return null;
            }
        }

        $candidates = [];

        foreach ($entries as $entry) {
            if ($entry['type'] === $parts['type'] && $entry['id'] === $parts['id'] && $entry['fullUrl'] !== null) {
                $candidates[] = $entry['fullUrl'];
            }
        }

        // With nothing of that type and id in the bundle, the reference validator reports plain
        // absence instead, which is a rule this class does not implement. See the class docblock.
        if ($candidates === []) {
            return null;
        }

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $this->message($value, $candidates, $source['fullUrl']),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * The absolute URL a relative reference resolves to, or null when the source entry yields no base.
     *
     * @param array{fullUrl: string|null, resource: object|null, type: string|null, id: string|null} $source
     */
    private function targetUrl(string $reference, array $source): ?string
    {
        $fullUrl = $source['fullUrl'];

        if ($fullUrl === null || preg_match(self::RESTFUL_FULL_URL, $fullUrl, $parts) !== 1) {
            return null;
        }

        // The tail is only a `[type]/[id]` to strip when the id is genuinely this resource's own.
        // `id_in_target_resource` turns on exactly this: the Composition has no id, so its fullUrl
        // does not decompose, and nothing it holds can resolve.
        if ($source['id'] === null || $parts['id'] !== $source['id']) {
            return null;
        }

        return $parts['base'] . $reference;
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

    /** Read a string-ish property, which may be a primitive wrapper, a bare string, or absent. */
    private function readString(object $node, string $property): ?string
    {
        $value = $this->readPublic($node, $property);

        if (is_string($value)) {
            return $value === '' ? null : $value;
        }

        if ($value instanceof \Stringable) {
            $string = (string) $value;

            return $string === '' ? null : $string;
        }

        return null;
    }

    private function readObject(object $node, string $property): ?object
    {
        $value = $this->readPublic($node, $property);

        return is_object($value) ? $value : null;
    }

    /**
     * A public property's value, or null when it is absent, non-public or never written.
     *
     * Membership of the public-name list covers both "declared" and "public"; an uninitialized
     * property holds nothing, and reading it directly would throw rather than answer.
     */
    private function readPublic(object $node, string $property): mixed
    {
        if (!in_array($property, $this->models->publicPropertyNames($node), true)) {
            return null;
        }

        if (!$this->models->isPropertyInitialized($node, $property)) {
            return null;
        }

        return $this->models->readInitializedValue($node, $property);
    }

    private function resourceType(object $node): ?string
    {
        $key = $node::class;

        if (array_key_exists($key, $this->resourceTypes)) {
            return $this->resourceTypes[$key];
        }

        $type       = null;
        $attributes = $this->attributes->classAttributes($node, FhirResource::class);

        if ($attributes !== []) {
            $type = $attributes[0]->getResourceType();
        }

        return $this->resourceTypes[$key] = $type;
    }

    private function isReference(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isReference[$key])) {
            return $this->isReference[$key];
        }

        $is         = false;
        $attributes = $this->attributes->classAttributes($node, FHIRComplexType::class);

        if ($attributes !== []) {
            $is = $attributes[0]->typeName === self::REFERENCE;
        }

        return $this->isReference[$key] = $is;
    }
}
