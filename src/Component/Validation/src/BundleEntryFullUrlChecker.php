<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;

/**
 * Reports `Bundle.entry.fullUrl` values that are not absolute URLs.
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

    /** @var array<class-string, bool> keyed by class name; reflection is not free */
    private array $isBundleEntry = [];

    /**
     * Walk a resource and report every non-absolute `Bundle.entry.fullUrl`.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited = [];

        return $this->walk($resource, '', $visited);
    }

    /**
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation>
     */
    private function walk(object $node, string $path, array &$visited): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];
        $ref          = new \ReflectionClass($node);
        $nodeIsEntry  = $this->isBundleEntry($ref);

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            // Deserializers bypass the constructor, so an absent field is uninitialized rather than
            // null; reading it would throw \Error and drop the whole case out of the comparison set.
            if ($prop->isInitialized($node) === false) {
                continue;
            }

            $value   = $prop->getValue($node);
            $subPath = $path === '' ? $prop->getName() : $path . '.' . $prop->getName();

            if ($nodeIsEntry && $prop->getName() === 'fullUrl') {
                $violation = $this->checkFullUrl($value, $subPath);

                if ($violation !== null) {
                    $violations[] = $violation;
                }
            }

            if (is_object($value)) {
                foreach ($this->walk($value, $subPath, $visited) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->walk($item, $subPath . '[' . $i . ']', $visited) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Apply the rule to one `fullUrl` value.
     *
     * An absent `fullUrl` is not a finding: the element is `0..1` and the reference validator reports
     * nothing for an entry that omits it. Many corpus bundles do.
     *
     * The message is the reference validator's, verbatim including the quoting, so a case that
     * reaches `EQUAL` here agrees on wording and not merely on count.
     */
    private function checkFullUrl(mixed $value, string $path): ?FHIRValidationViolation
    {
        $fullUrl = $this->readUri($value);

        if ($fullUrl === null || $fullUrl === '') {
            return null;
        }

        if (preg_match(self::SCHEME, $fullUrl) === 1) {
            return null;
        }

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: sprintf("The fullUrl must be an absolute URL (not '%s')", $fullUrl),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Read the lexeme out of whatever the deserializer put on the property.
     *
     * `fullUrl` is a `?UriPrimitive` in all three versions, but the wrapper is \Stringable and its own
     * `value` may be absent (an extension-only element), which stringifies to '' and is absence rather
     * than a relative URL.
     */
    private function readUri(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param \ReflectionClass<object> $ref
     */
    private function isBundleEntry(\ReflectionClass $ref): bool
    {
        $key = $ref->getName();

        if (isset($this->isBundleEntry[$key])) {
            return $this->isBundleEntry[$key];
        }

        $isEntry    = false;
        $attributes = $ref->getAttributes(FHIRBackboneElement::class);

        if ($attributes !== []) {
            $isEntry = $attributes[0]->newInstance()->elementPath === self::BUNDLE_ENTRY;
        }

        return $this->isBundleEntry[$key] = $isEntry;
    }
}
