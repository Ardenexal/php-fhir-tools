<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports entries of a document or message Bundle that the first entry cannot reach by following links.
 *
 * A document is the Composition plus what it points at, and a message is the MessageHeader plus what
 * it points at. An entry nothing leads to is not part of either. The HL7 Java reference validator walks
 * the links between entries and reports every entry the walk misses; we did not, so orphaned entries
 * validated clean.
 *
 * ## The walk
 *
 * The rule follows the reference validator's `BundleValidator.checkAllInterlinked`, step for step, so
 * that both validators reach the same verdict on the same bundle:
 *
 * 1. Every entry that holds a resource gets its **links**: each `Reference.reference` and each `uri`,
 *    `url` or `canonical` value anywhere inside the entry (inside `contained` too), that resolves to
 *    another entry. A link to the entry itself is dropped.
 * 2. **Forwards.** Starting from the first entry, follow links until nothing new is found. An entry
 *    a `Bundle.link` names as the `stylesheet` is a starting point too.
 * 3. **Backwards.** An entry the walk missed, but which links *into* the visited set, is added together
 *    with everything it leads to, and the step repeats until no entry is added. Each one added this way
 *    gets a finding of its own: a warning in a document, information in a message. A Provenance is
 *    the exception and stays silent, because pointing back at what it describes is what the spec says
 *    Provenance does (R4 section 3.3.1). `uk-msg` shows the reference validator exempts it in a
 *    message too.
 * 4. **Orphans.** Every entry still not visited is reported: an error in a document, a warning in a
 *    message.
 *
 * ## Resolving a link
 *
 * Only what the Bundle itself can answer. An absolute value (it has a scheme) matches the entry whose
 * `fullUrl` is that value, once any `#fragment` is dropped. A relative `Type/id` takes its base from the referring entry's `fullUrl`,
 * which must read `[base]/[type]/[id]` with `[id]` equal to that resource's own id; see
 * {@see BundleReferenceResolutionChecker} for the fixtures that pin this rule down. A `_history/n` suffix
 * is matched against the entry's `meta.versionId`. A value matching two entries links to neither: the
 * reference validator reports that ambiguity as a separate finding, and following either entry would
 * be a guess.
 *
 * A `#id` fragment, an id without a type, and a reference under a non-RESTful or `urn:` `fullUrl`
 * resolve to nothing. That is why every entry of `japanese-utf8-ok` (bare-uuid references) and of
 * `bundle-urn` (relative references under `urn:uuid:` fullUrls) is reported.
 *
 * ## Why this is not a Symfony constraint
 *
 * Same reason as {@see BundleEntryFullUrlChecker}: no single element holds the rule. It needs every
 * entry in hand before it can judge any one of them.
 *
 * ## Scoping
 *
 * Only the root Bundle is walked. A document or message nested inside an entry is not checked, which
 * costs nothing in the R4, R4B and R5 corpus. The first entry must be the Composition (document) or the
 * MessageHeader (message); when it is not, `bdl-11` / `bdl-12` already report the bundle, and walking
 * from the wrong resource would report every other entry on top of that.
 */
final class BundleReachabilityChecker
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

    /** The resource each walked Bundle type must open with, and which the messages name. */
    private const array FIRST_RESOURCE = [
        'document' => 'Composition',
        'message'  => 'MessageHeader',
    ];

    /** The resource that may only point backwards without a finding, in a document or a message. */
    private const string PROVENANCE = 'Provenance';

    /** The `Bundle.link` relation that brings a document's stylesheet into it. */
    private const string STYLESHEET = 'stylesheet';

    /** The primitive types whose values the reference validator treats as links, like a Reference. */
    private const array LINK_PRIMITIVES = ['uri', 'url', 'canonical'];

    /** A value with a URI scheme, `http:` or `urn:` for example, resolves by `fullUrl` alone. */
    private const string ABSOLUTE = '~\A[A-Za-z][A-Za-z0-9+.\-]*:~';

    /** A relative reference, `[Type]/[id]`, optionally pinned to a version. */
    private const string RELATIVE_REFERENCE = '~\A(?<type>[A-Z][A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})(?:/_history/(?<version>[^/]+))?\z~';

    /** A version suffix on an absolute reference. */
    private const string HISTORY_SUFFIX = '~\A(?<url>.+)/_history/(?<version>[^/]+)\z~';

    /** A `fullUrl` that yields a base. The same rule as {@see BundleReferenceResolutionChecker}, empty base included. */
    private const string RESTFUL_FULL_URL = '~\A(?<base>(?:.*/)?)(?<type>[A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})\z~';

    /** @var array<class-string, string|null> FHIR resource type per class; reflection is not free */
    private array $resourceTypes = [];

    /** @var array<class-string, string|null> FHIR type of a complex type or primitive, per class */
    private array $typeNames = [];

    /**
     * Walk a document or message Bundle and report each entry its first entry cannot reach.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        if ($this->resourceType($resource) !== self::BUNDLE) {
            return [];
        }

        $bundleType = $this->readString($resource, 'type');

        if ($bundleType === null || !isset(self::FIRST_RESOURCE[$bundleType])) {
            return [];
        }

        $isDocument = $bundleType === 'document';
        $anchor     = self::FIRST_RESOURCE[$bundleType];
        $entries    = $this->entries($resource);

        if ($entries === [] || array_key_first($entries) !== 0 || $entries[0]['type'] !== $anchor) {
            return [];
        }

        $byFullUrl = [];

        foreach ($entries as $index => $entry) {
            if ($entry['fullUrl'] !== null) {
                $byFullUrl[$entry['fullUrl']][] = $index;
            }
        }

        // Resolve every entry's links once, up front: the backwards step reads them repeatedly.
        $targets = [];

        foreach ($entries as $index => $entry) {
            $targets[$index] = [];
            $visited         = [];

            foreach ($this->linkValues($entry['entry'], $visited) as $value) {
                $target = $this->resolve($value, $entry, $entries, $byFullUrl);

                if ($target !== null && $target !== $index) {
                    $targets[$index][$target] = $target;
                }
            }
        }

        $violations = [];
        $reached    = [];
        $this->visit(0, $targets, $reached);

        foreach ($this->stylesheets($resource, $entries) as $stylesheet) {
            $this->visit($stylesheet, $targets, $reached);
        }

        // Backwards: an unreached entry that links into the reached set joins it, with everything it
        // leads to. The reference validator repeats whole passes until one adds nothing, and an entry
        // added early in a pass already counts for the entries after it, so the same order is kept.
        do {
            $added = false;

            foreach ($entries as $index => $entry) {
                if (isset($reached[$index]) || array_intersect_key($targets[$index], $reached) === []) {
                    continue;
                }

                $violation = $this->backwardsViolation($index, $entry, $isDocument, $anchor);

                if ($violation !== null) {
                    $violations[] = $violation;
                }

                $this->visit($index, $targets, $reached);
                $added = true;
            }
        } while ($added);

        foreach ($entries as $index => $entry) {
            if (isset($reached[$index])) {
                continue;
            }

            $violations[] = new FHIRValidationViolation(
                severity: $isDocument ? 'error' : 'warning',
                path: 'entry[' . $index . ']',
                message: $isDocument
                    ? sprintf("Entry %s isn't reachable by traversing links (forward or backward) from the %s", $this->entryName($entry), $anchor)
                    : sprintf("Entry %s isn't reachable by traversing links (forward or backward) from the %s, so its presence should be reviewed (is it needed to process the message?)", $this->entryName($entry), $anchor),
                constraintClass: self::class,
                profileGroup: null,
                invariantKey: null,
            );
        }

        return $violations;
    }

    /**
     * The entries a `Bundle.link` with relation `stylesheet` names: the document's CSS, which the
     * Composition does not reference but which belongs to the document all the same.
     *
     * The url matches an entry's `fullUrl` exactly, or, when relative, its tail. `document-css` links
     * `Binary/css` to the entry `http://hl7.org/fhir/test/Binary/css` under a Composition whose id
     * disagrees with its own fullUrl, so the base rule for references cannot be what pairs them, and
     * the reference validator does not report that Binary.
     *
     * @param array<int, array{entry: object, fullUrl: string|null, resource: object, type: string|null}> $entries
     *
     * @return list<int>
     */
    private function stylesheets(object $bundle, array $entries): array
    {
        $links = $this->readPublic($bundle, 'link');

        if (!is_array($links)) {
            return [];
        }

        $found = [];

        foreach ($links as $link) {
            if (!is_object($link) || $this->readString($link, 'relation') !== self::STYLESHEET) {
                continue;
            }

            $url = $this->readString($link, 'url');

            if ($url === null) {
                continue;
            }

            $isAbsolute = preg_match(self::ABSOLUTE, $url) === 1;

            foreach ($entries as $index => $entry) {
                $fullUrl = $entry['fullUrl'];

                if ($fullUrl !== null && ($fullUrl === $url || (!$isAbsolute && str_ends_with($fullUrl, '/' . $url)))) {
                    $found[] = $index;
                }
            }
        }

        return $found;
    }

    /**
     * Mark an entry reached, then everything its links lead to.
     *
     * @param array<int, array<int, int>> $targets
     * @param array<int, true>            $reached
     */
    private function visit(int $start, array $targets, array &$reached): void
    {
        $pending = [$start];

        while ($pending !== []) {
            $index = array_pop($pending);

            if (isset($reached[$index])) {
                continue;
            }

            $reached[$index] = true;

            foreach ($targets[$index] as $target) {
                if (!isset($reached[$target])) {
                    $pending[] = $target;
                }
            }
        }
    }

    /**
     * The finding for an entry reached only by pointing back into the document or message.
     *
     * @param array{entry: object, fullUrl: string|null, resource: object, type: string|null} $entry
     */
    private function backwardsViolation(int $index, array $entry, bool $isDocument, string $anchor): ?FHIRValidationViolation
    {
        if ($entry['type'] === self::PROVENANCE) {
            return null;
        }

        return new FHIRValidationViolation(
            severity: $isDocument ? 'warning' : 'info',
            path: 'entry[' . $index . ']',
            message: $isDocument
                ? sprintf("Entry %s isn't reachable by traversing forwards from the %s. Only Provenance is approved to be used this way (R4 section 3.3.1)", $this->entryName($entry), $anchor)
                : sprintf("Entry %s isn't reachable by traversing forwards from the %s. Check that this is meant to be included (needed to process the message)", $this->entryName($entry), $anchor),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * How the reference validator names an entry: its quoted `fullUrl`, or nothing at all without one,
     * which leaves the double space in `Entry  isn't reachable`. Kept so the two findings pair.
     *
     * @param array{entry: object, fullUrl: string|null, resource: object, type: string|null} $entry
     */
    private function entryName(array $entry): string
    {
        return $entry['fullUrl'] === null ? '' : "'" . $entry['fullUrl'] . "'";
    }

    /**
     * The entries that hold a resource, by position. An entry without one takes no part in the walk.
     *
     * @return array<int, array{entry: object, fullUrl: string|null, resource: object, type: string|null}>
     */
    private function entries(object $bundle): array
    {
        $value = $this->readPublic($bundle, 'entry');

        if (!is_array($value)) {
            return [];
        }

        $entries = [];

        foreach ($value as $index => $entry) {
            if (!is_int($index) || !is_object($entry)) {
                continue;
            }

            $resource = $this->readPublic($entry, 'resource');

            if (!is_object($resource)) {
                continue;
            }

            $entries[$index] = [
                'entry'    => $entry,
                'fullUrl'  => $this->readString($entry, 'fullUrl'),
                'resource' => $resource,
                'type'     => $this->resourceType($resource),
            ];
        }

        return $entries;
    }

    /**
     * Every value beneath a node that may link to another entry: each `Reference.reference`, and each
     * `uri`, `url` or `canonical` primitive. `#` fragments point inside the resource and are dropped.
     *
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<string>
     */
    private function linkValues(object $node, array &$visited): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $found        = [];
        $type         = $this->typeName($node);

        $value = match (true) {
            $type === self::REFERENCE                         => $this->readString($node, 'reference'),
            in_array($type, self::LINK_PRIMITIVES, true)      => $this->readString($node, 'value'),
            default                                           => null,
        };

        if ($value !== null && !str_starts_with($value, '#')) {
            $found[] = $value;
        }

        foreach ($this->models->initializedPublicValues($node) as $child) {
            $children = is_array($child) ? $child : [$child];

            foreach ($children as $item) {
                if (is_object($item)) {
                    array_push($found, ...$this->linkValues($item, $visited));
                }
            }
        }

        return $found;
    }

    /**
     * The position of the one entry a link value resolves to, or null when it resolves to none or to
     * several.
     *
     * @param array{entry: object, fullUrl: string|null, resource: object, type: string|null}             $source
     * @param array<int, array{entry: object, fullUrl: string|null, resource: object, type: string|null}> $entries
     * @param array<string, list<int>>                                                                    $byFullUrl
     */
    private function resolve(string $value, array $source, array $entries, array $byFullUrl): ?int
    {
        if (preg_match(self::ABSOLUTE, $value) === 1) {
            // `urn:uuid:…#section` points into the entry `urn:uuid:…`, which is all the walk needs.
            $value = explode('#', $value, 2)[0];

            if (isset($byFullUrl[$value])) {
                return $this->unique($byFullUrl[$value], null, $entries);
            }

            if (preg_match(self::HISTORY_SUFFIX, $value, $parts) === 1 && isset($byFullUrl[$parts['url']])) {
                return $this->unique($byFullUrl[$parts['url']], $parts['version'], $entries);
            }

            return null;
        }

        if (preg_match(self::RELATIVE_REFERENCE, $value, $parts) !== 1) {
            return null;
        }

        $base = $this->base($source);

        if ($base === null) {
            return null;
        }

        $target = $base . $parts['type'] . '/' . $parts['id'];

        if (!isset($byFullUrl[$target])) {
            return null;
        }

        $version = ($parts['version'] ?? '') === '' ? null : $parts['version'];

        return $this->unique($byFullUrl[$target], $version, $entries);
    }

    /**
     * The one candidate left once a version, if any, has narrowed them, or null when that is not one.
     *
     * @param list<int>                                                                                   $candidates
     * @param array<int, array{entry: object, fullUrl: string|null, resource: object, type: string|null}> $entries
     */
    private function unique(array $candidates, ?string $version, array $entries): ?int
    {
        if ($version !== null) {
            $candidates = array_values(array_filter(
                $candidates,
                fn (int $index): bool => $this->versionId($entries[$index]['resource']) === $version,
            ));
        }

        return count($candidates) === 1 ? $candidates[0] : null;
    }

    /**
     * The base a relative reference inside this entry resolves against, or null when it yields none.
     *
     * @param array{entry: object, fullUrl: string|null, resource: object, type: string|null} $source
     */
    private function base(array $source): ?string
    {
        $fullUrl = $source['fullUrl'];

        if ($fullUrl === null || preg_match(self::RESTFUL_FULL_URL, $fullUrl, $parts) !== 1) {
            return null;
        }

        // Only a tail naming this resource's own id can be stripped to leave a base.
        if ($parts['id'] !== $this->readString($source['resource'], 'id')) {
            return null;
        }

        return $parts['base'];
    }

    private function versionId(object $resource): ?string
    {
        $meta = $this->readPublic($resource, 'meta');

        return is_object($meta) ? $this->readString($meta, 'versionId') : null;
    }

    /** Read a string-ish property, which may be a primitive wrapper, a bare string, or absent. */
    private function readString(object $node, string $property): ?string
    {
        $value = $this->readPublic($node, $property);

        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        }

        if (is_string($value)) {
            return $value === '' ? null : $value;
        }

        if ($value instanceof \Stringable) {
            $string = (string) $value;

            return $string === '' ? null : $string;
        }

        return null;
    }

    /**
     * A public property's value, or null when it is absent, non-public or never written.
     *
     * Deserializers bypass the constructor, so an absent field is uninitialized rather than null, and
     * reading it directly would throw rather than answer.
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

        $attributes = $this->attributes->classAttributes($node, FhirResource::class);

        return $this->resourceTypes[$key] = $attributes === [] ? null : $attributes[0]->getResourceType();
    }

    /** The FHIR type a complex-type or primitive class stands for, or null for anything else. */
    private function typeName(object $node): ?string
    {
        $key = $node::class;

        if (array_key_exists($key, $this->typeNames)) {
            return $this->typeNames[$key];
        }

        $type    = null;
        $complex = $this->attributes->classAttributes($node, FHIRComplexType::class);

        if ($complex !== []) {
            $type = $complex[0]->typeName;
        } else {
            $primitive = $this->attributes->classAttributes($node, FHIRPrimitive::class);

            if ($primitive !== []) {
                $type = $primitive[0]->primitiveType;
            }
        }

        return $this->typeNames[$key] = $type;
    }
}
