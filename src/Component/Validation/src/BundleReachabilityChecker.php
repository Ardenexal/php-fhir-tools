<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

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
 * {@see BundleEntryIndex} decides which entries a value names, by the same rules
 * {@see BundleReferenceResolutionChecker} reports against. A value that names two entries holding a
 * resource links to neither: the reference validator reports that ambiguity as a separate finding, and
 * following either entry would be a guess.
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
 *
 * @phpstan-import-type IndexedEntry from BundleEntryIndex
 */
final class BundleReachabilityChecker
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

    /**
     * Walk a document or message Bundle and report each entry its first entry cannot reach.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        if ($this->reader->resourceType($resource) !== self::BUNDLE) {
            return [];
        }

        $bundleType = $this->reader->readString($resource, 'type');

        if ($bundleType === null || !isset(self::FIRST_RESOURCE[$bundleType])) {
            return [];
        }

        $isDocument = $bundleType === 'document';
        $anchor     = self::FIRST_RESOURCE[$bundleType];
        $index      = $this->reader->index($resource);

        // An entry without a resource takes no part in the walk.
        $entries = array_filter($index->entries, static fn (array $entry): bool => $entry['resource'] !== null);

        if ($entries === [] || array_key_first($entries) !== 0 || $entries[0]['type'] !== $anchor) {
            return [];
        }

        // Resolve every entry's links once, up front: the backwards step reads them repeatedly.
        $targets = [];

        foreach ($entries as $position => $entry) {
            $targets[$position] = [];
            $visited            = [];

            foreach ($this->linkValues($entry['entry'], $visited) as $value) {
                $target = $this->resolve($value, $position, $index);

                if ($target !== null && $target !== $position) {
                    $targets[$position][$target] = $target;
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
     * @param array<int, IndexedEntry> $entries
     *
     * @return list<int>
     */
    private function stylesheets(object $bundle, array $entries): array
    {
        $links = $this->reader->readPublic($bundle, 'link');

        if (!is_array($links)) {
            return [];
        }

        $found = [];

        foreach ($links as $link) {
            if (!is_object($link) || $this->reader->readString($link, 'relation') !== self::STYLESHEET) {
                continue;
            }

            $url = $this->reader->readString($link, 'url');

            if ($url === null) {
                continue;
            }

            $isAbsolute = BundleEntryIndex::isAbsolute($url);

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
     * @param IndexedEntry $entry
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
     * @param IndexedEntry $entry
     */
    private function entryName(array $entry): string
    {
        return $entry['fullUrl'] === null ? '' : "'" . $entry['fullUrl'] . "'";
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
        $type         = $this->reader->typeName($node);

        $value = match (true) {
            $type === self::REFERENCE                         => $this->reader->readString($node, 'reference'),
            in_array($type, self::LINK_PRIMITIVES, true)      => $this->reader->readString($node, 'value'),
            default                                           => null,
        };

        if ($value !== null && !str_starts_with($value, '#')) {
            $found[] = $value;
        }

        foreach ($this->reader->children($node) as $child) {
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
     * The position of the one entry holding a resource that a link value resolves to, or null when it
     * resolves to none or to several. Two matches are ambiguous, and following either would be a guess.
     */
    private function resolve(string $value, int $source, BundleEntryIndex $index): ?int
    {
        if (BundleEntryIndex::isAbsolute($value)) {
            $matches = $index->resolveAbsolute($value);
        } else {
            $parts   = BundleEntryIndex::parseRelative($value);
            $matches = $parts === null ? [] : $index->resolveRelative($parts['type'], $parts['id'], $parts['version'], $source);
        }

        $matches = array_values(array_filter($matches, static fn (int $match): bool => $index->entries[$match]['resource'] !== null));

        return count($matches) === 1 ? $matches[0] : null;
    }
}
