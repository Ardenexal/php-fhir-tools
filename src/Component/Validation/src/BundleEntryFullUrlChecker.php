<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResourceType as R4ResourceType;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ResourceType as R4BResourceType;
use Ardenexal\FHIRTools\Component\Models\R5\Enum\ResourceType as R5ResourceType;

/**
 * Reports the `Bundle.entry.fullUrl` rules the HL7 Java reference validator applies:
 *
 * 1. A present `fullUrl` must be an absolute URL.
 * 2. A `fullUrl` that looks like a RESTful server URL (`[base]/[type]/[id]`) must end with the entry
 *    resource's own type and id, and the resource must have an id.
 * 3. An entry without a `fullUrl` is reported when its resource holds a relative reference, because
 *    there is nothing to resolve that reference against.
 * 4. An entry without a `fullUrl` is reported in every Bundle type except `transaction`,
 *    `transaction-response`, `batch` and `batch-response`.
 *
 * Rules 3 and 4 are independent and can both fire on one entry: `japanese-utf8-ok` reports both on
 * its Procedure entry (relative `subject` reference) and only rule 4 on the Device entry after it,
 * and `bundle-ea-testcase`, a transaction, reports only rule 3 on its MeasureReport entry. The rest of
 * this docblock is about rule 1, which came first.
 *
 * The spec is explicit — `Bundle.entry.fullUrl` is the "Absolute URL for resource (server address, or
 * UUID/OID)" — but nothing in the generated models enforces it. `fullUrl` is a `?UriPrimitive`
 * carrying only the `uri` whitespace regex, and the one invariant on the element (`bdl-8`) checks
 * that it is not version-specific, not that it is absolute. So a bundle full of `Observation/1`
 * entries validated clean while the HL7 Java reference validator reported one error per entry.
 *
 * This was the largest single gap in the corpus: **67 errors across 7 cases**, the biggest being
 * `bundle-duplicate-ids-not` at 39. Worth noting that case's name is misleading — none of its errors
 * concern duplicate ids.
 *
 * ## Why this is not a Symfony constraint
 *
 * Same reason as {@see CodingSystemChecker}: no `Assert\Valid` cascades into primitive-typed
 * properties, so a `Regex` on the generated `fullUrl` would never run. This pass walks the tree
 * itself and is independent of what the generator emits.
 *
 * ## Scoping by type, never by property name
 *
 * The node is identified by `#[FHIRBackboneElement(elementPath: 'Bundle.entry')]`, which covers the
 * R4, R4B and R5 classes without version routing. `fullUrl` happens to be unique to `Bundle.entry`
 * in the base spec, so a property-name rule would work today — but it would silently start matching
 * any profile or logical model that introduces the name, and the attribute costs nothing.
 *
 * ## The ABOVE question, settled against the corpus before this was written
 *
 * Every relative `fullUrl` in the vendored corpus — all 67, across both JSON and XML fixtures — is
 * flagged by Java. There is no case where a relative value is tolerated. Three fixtures looked like
 * counter-examples and are not: `bundle-india` and `bundle-india-bad` declare a `java/` oracle path
 * that does not exist on disk, so the harness drops them as `no-oracle` and Java's "silence" is the
 * absence of a result rather than approval; `japanese-utf8` is R5 and unreadable, so it never reaches
 * this rule.
 *
 * This rule is therefore *not* the `fullUrl` capability the nested-cascade plan flagged as needing an
 * algorithm decision first. That one is reference *resolution* — matching `Reference.reference`
 * against an entry's `fullUrl`, where `bundle-urn` and `bad-bundle-reference-type-4` genuinely treat
 * the same `urn:uuid` shape in opposite ways. This is the absolute-URL shape test, and the two are
 * independent.
 */
final class BundleEntryFullUrlChecker
{
    /** The backbone element this rule applies to, read from the class attribute. */
    private const string BUNDLE_ENTRY = 'Bundle.entry';

    /**
     * A URI is absolute when it opens with a scheme.
     *
     * Deliberately not a `://` test: `urn:uuid:…` and `urn:oid:…` are the spec's own suggested forms
     * for a `fullUrl` and are absolute. A `://` test would flag every one of them, which is how this
     * rule would manufacture the `ABOVE` cases it is written to avoid.
     */
    private const string SCHEME = '/\A[A-Za-z][A-Za-z0-9+.\-]*:/';

    /** Identifies a Bundle node, so its entries learn which Bundle type they sit in. */
    private const string BUNDLE = 'Bundle';

    /** Identifies a reference node, by FHIR type rather than by the presence of a property name. */
    private const string REFERENCE = 'Reference';

    /** The Bundle types whose entries may omit `fullUrl` (rule 4). */
    private const array FULL_URL_OPTIONAL_BUNDLE_TYPES = ['transaction', 'transaction-response', 'batch', 'batch-response'];

    /**
     * A `fullUrl` that "looks like a RESTful server URL" (rule 2): an `http(s)` base, then
     * `[type]/[id]`, then an optional `_history/[vid]`.
     *
     * The base path uses the reference validator's own narrow character class: no hyphen, so
     * `http://my-server.org/Patient/1` does not look RESTful to it and is not checked. The base needs
     * at least one segment, so a host is never read as the type (`http://Patient/p2` is not checked). A relative `fullUrl` is never checked by this rule: `mni-patientOverview-bundle-example1b`
     * holds a Composition with id `1a` at `Composition/1`, and the reference validator reports only
     * that the URL is not absolute (rule 1). The type segment is confirmed against the resource type
     * enums after the match.
     */
    private const string RESTFUL_FULL_URL = '~\Ahttps?://(?:[A-Za-z0-9\\\\.:%$]*/)+(?<type>[A-Za-z]+)/[A-Za-z0-9\-.]{1,64}(?:/_history/[A-Za-z0-9\-.]{1,64})?\z~';

    /** @var array<class-string, bool> keyed by class name; the attribute read is cached upstream too, this saves the call */
    private array $isBundleEntry = [];

    /** @var array<class-string, FhirResource|null> resource attribute per class; reflection is not free */
    private array $resourceAttributes = [];

    /** @var array<class-string, bool> whether the class is a `Reference` */
    private array $isReference = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Walk a resource and report every non-absolute `Bundle.entry.fullUrl`.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited = [];

        return $this->walk($resource, '', $visited, null);
    }

    /**
     * @param array<int, true> $visited    spl_object_id keys of already-visited objects (cycle guard)
     * @param string|null      $bundleType the parent Bundle's `type` when `$node` is one of its direct
     *                                     children, '' when that Bundle has no type, null otherwise
     *
     * @return list<FHIRValidationViolation>
     */
    private function walk(object $node, string $path, array &$visited, ?string $bundleType): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = $this->isBundleEntry($node) ? $this->checkEntry($node, $path, $bundleType) : [];

        // Only a Bundle's direct children (its entries) learn its type; a nested Bundle sets its own.
        $childBundleType = $this->resourceType($node) === self::BUNDLE
            ? ($this->readString($node, 'type') ?? '')
            : null;

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                foreach ($this->walk($value, $subPath, $visited, $childBundleType) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->walk($item, $subPath . '[' . $i . ']', $visited, $childBundleType) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Apply the four rules to one `Bundle.entry`.
     *
     * Every message is the reference validator's, verbatim including the quoting, so a case that
     * reaches `EQUAL` here agrees on wording and not merely on count. Rule 1 reports on `fullUrl`
     * itself; the others report on the entry, which is where the reference validator puts them.
     *
     * @param string|null $bundleType see {@see walk()}; null means the entry was reached without its
     *                                Bundle, so rule 4 cannot be judged and is skipped
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkEntry(object $entry, string $path, ?string $bundleType): array
    {
        $fullUrl    = $this->readLexeme($entry, 'fullUrl');
        $resource   = $this->readPublic($entry, 'resource');
        $resource   = is_object($resource) ? $resource : null;
        $violations = [];

        // An empty `"fullUrl": ""` is present, not absent: the reference validator reports it only as
        // an empty value (`bundle-bad-empty`), which other passes cover, and none of these four rules.
        if ($fullUrl === '') {
            return [];
        }

        if ($fullUrl !== null) {
            if (preg_match(self::SCHEME, $fullUrl) !== 1) {
                $violations[] = $this->violation(
                    ($path === '' ? '' : $path . '.') . 'fullUrl',
                    sprintf("The fullUrl must be an absolute URL (not '%s')", $fullUrl),
                );
            }

            $restful = $resource === null ? null : $this->checkRestfulFullUrl($fullUrl, $resource);

            if ($restful !== null) {
                $violations[] = $this->violation($path, $restful);
            }

            return $violations;
        }

        if ($resource !== null && $this->holdsRelativeReference($resource, [])) {
            $violations[] = $this->violation($path, 'Bundle entry missing fullUrl');
        }

        if ($bundleType !== null && !in_array($bundleType, self::FULL_URL_OPTIONAL_BUNDLE_TYPES, true)) {
            $violations[] = $this->violation(
                $path,
                'Except for transactions and batches, each entry in a Bundle must have a fullUrl which is the identity of the resource in the entry',
            );
        }

        return $violations;
    }

    /**
     * Rule 2: a RESTful-looking `fullUrl` must end with `/[type]/[id]` of the resource it holds.
     *
     * @return string|null the finding's message, or null when the rule is satisfied or does not apply
     */
    private function checkRestfulFullUrl(string $fullUrl, object $resource): ?string
    {
        $fhirVersion = $this->resourceAttribute($resource)?->fhirVersion;

        if (preg_match(self::RESTFUL_FULL_URL, $fullUrl, $parts) !== 1 || !$this->isKnownResourceType($parts['type'], $fhirVersion)) {
            return null;
        }

        $type = $this->resourceType($resource);
        $id   = $this->readString($resource, 'id');

        if ($id === null) {
            return sprintf("The fullUrl '%s' looks like a RESTful server URL, but the resource has no id", $fullUrl);
        }

        $expected = '/' . $type . '/' . $id;

        if (str_ends_with($fullUrl, $expected)) {
            return null;
        }

        return sprintf(
            "The fullUrl '%s' looks like a RESTful server URL, so it must end with the correct type and id (%s)",
            $fullUrl,
            $expected,
        );
    }

    /**
     * Whether a type name is a resource type in the entry resource's FHIR release, as the reference
     * validator decides it. `ActorDefinition` exists only in R5, so an R4 entry at
     * `http://example.org/ActorDefinition/1` does not look RESTful. A resource class that declares no
     * release falls back to any release.
     */
    private function isKnownResourceType(string $type, ?string $fhirVersion): bool
    {
        return match ($fhirVersion) {
            'R4'    => R4ResourceType::tryFrom($type)  !== null,
            'R4B'   => R4BResourceType::tryFrom($type) !== null,
            'R5'    => R5ResourceType::tryFrom($type)  !== null,
            default => R4ResourceType::tryFrom($type)  !== null
                || R4BResourceType::tryFrom($type)     !== null
                || R5ResourceType::tryFrom($type)      !== null,
        };
    }

    /**
     * Rule 3: whether a resource holds a `Reference.reference` that needs a base to resolve.
     *
     * Relative means neither absolute (has a scheme) nor a `#` fragment into `contained`. `contained`
     * itself is not descended into, and neither is a nested Bundle's entry: each of those resolves
     * against its own rules or its own entry's `fullUrl`, never this one's.
     *
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     */
    private function holdsRelativeReference(object $node, array $visited): bool
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return false;
        }

        $visited[$id] = true;

        if ($this->isReference($node)) {
            $reference = $this->readString($node, 'reference');

            if ($reference !== null && !str_starts_with($reference, '#') && preg_match(self::SCHEME, $reference) !== 1) {
                return true;
            }

            // Not relative itself, but a Reference can hold another one in `identifier.assigner`.
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            if ($name === 'contained') {
                continue;
            }

            $items = is_array($value) ? $value : [$value];

            foreach ($items as $item) {
                if (is_object($item) && !$this->isBundleEntry($item) && $this->holdsRelativeReference($item, $visited)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function violation(string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /** A property's string value, with an empty value read as absent. */
    private function readString(object $node, string $property): ?string
    {
        $value = $this->readLexeme($node, $property);

        return $value === '' ? null : $value;
    }

    /**
     * Read the lexeme out of whatever the deserializer put on the property, keeping an empty string
     * apart from absence.
     *
     * `fullUrl` is a `?UriPrimitive` in all three versions, and the wrapper's own `value` may be absent
     * (an extension-only element). That is absence, not a relative URL. A code primitive may hold its
     * value as a backed enum.
     */
    private function readLexeme(object $node, string $property): ?string
    {
        $value = $this->readPublic($node, $property);

        if (is_object($value) && in_array('value', $this->models->publicPropertyNames($value), true)) {
            $value = $this->readPublic($value, 'value');
        }

        if ($value instanceof \BackedEnum) {
            $value = (string) $value->value;
        } elseif ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        return is_string($value) ? $value : null;
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
        return $this->resourceAttribute($node)?->getResourceType();
    }

    private function resourceAttribute(object $node): ?FhirResource
    {
        $key = $node::class;

        if (array_key_exists($key, $this->resourceAttributes)) {
            return $this->resourceAttributes[$key];
        }

        // Hierarchy, not concrete class: a generated profile class such as `BatchBundleProfile` extends
        // `BundleResource` without repeating its `#[FhirResource]`, and is still a Bundle.
        $attributes = $this->attributes->classAttributesInHierarchy($node, FhirResource::class);

        return $this->resourceAttributes[$key] = $attributes === [] ? null : $attributes[0];
    }

    private function isReference(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isReference[$key])) {
            return $this->isReference[$key];
        }

        $attributes = $this->attributes->classAttributes($node, FHIRComplexType::class);

        return $this->isReference[$key] = $attributes !== [] && $attributes[0]->typeName === self::REFERENCE;
    }

    /**
     * Reads the concrete class only, matching the raw reflection. Three classes declare the
     * `Bundle.entry` element path -- one per FHIR version -- and none is extended anywhere in the
     * generated or CDA models, so no ancestor carries a competing path. Measured, not assumed.
     */
    private function isBundleEntry(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isBundleEntry[$key])) {
            return $this->isBundleEntry[$key];
        }

        $isEntry    = false;
        $attributes = $this->attributes->classAttributes($node, FHIRBackboneElement::class);

        if ($attributes !== []) {
            $isEntry = $attributes[0]->elementPath === self::BUNDLE_ENTRY;
        }

        return $this->isBundleEntry[$key] = $isEntry;
    }
}
