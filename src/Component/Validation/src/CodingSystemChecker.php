<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports `Coding.system` values that are not usable as a code-system identity.
 *
 * ## Why this is not a Symfony constraint
 *
 * The generated `Coding.system` is a `?UriPrimitive` and carries only the `uri` whitespace regex.
 * Even a stricter constraint there would never run: no `Assert\Valid` cascades into primitive-typed
 * properties, and adding one is barred while the emitted primitive patterns carry no PCRE
 * delimiters. This pass walks the tree itself, so the rule is independent of the generator.
 *
 * ## Scoping by type, never by property name
 *
 * The node is identified by the `#[FHIRComplexType(typeName: 'Coding')]` class attribute. That is
 * load-bearing: `ContactPoint.system` legitimately holds "phone"/"email" and `Quantity.system` is
 * also a `uri`, so a rule scoped by the property name would emit dozens of false errors across the
 * corpus. The attribute also covers the R4, R4B and R5 classes without version routing.
 *
 * ## What is deliberately not checked
 *
 * Whether the URL resolves. Java's `isValueSet()` resolves the canonical against the loaded core
 * package; we have no such registry, so the second rule is a URL *shape* test. A genuine CodeSystem
 * whose URL happened to contain a `/ValueSet/` path segment would be a false positive — no such URL
 * exists in the conformance corpus, and every oracle-positive value matches the shape. Nothing here
 * looks up a terminology server: the "could not be found" / "none of the codings are in the value
 * set" findings the reference validator raises alongside these are warnings and out of scope.
 */
final class CodingSystemChecker
{
    /** The FHIR complex type this rule applies to, read from the class attribute. */
    private const string CODING = 'Coding';

    /**
     * A URI is absolute when it opens with a scheme.
     *
     * Deliberately not a `://` test: `urn:oid:…`, `urn:ietf:bcp:47` and `urn:iso:std:iso:3166` are
     * common, absolute, and correct — a `://` test would flag every one of them.
     */
    private const string SCHEME = '/\A[A-Za-z][A-Za-z0-9+.\-]*:/';

    /** @var array<class-string, bool> keyed by class name; the attribute read is cached upstream too, this saves the call */
    private array $isCoding = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Walk a resource and report every unusable `Coding.system`.
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
        $nodeIsCoding = $this->isCoding($node);

        foreach ($this->models->publicPropertyNames($node) as $name) {
            // Deserializers bypass the constructor, so an absent field is uninitialized rather than
            // null; reading it would throw \Error and drop the whole case out of the comparison set.
            // The guarded read answers null for both, and every branch below skips on null anyway.
            if (!$this->models->isPropertyInitialized($node, $name)) {
                continue;
            }

            $value   = $this->models->readInitializedValue($node, $name);
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if ($nodeIsCoding && $name === 'system') {
                $violation = $this->checkSystem($value, $subPath);

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
     * Apply the two rules to one `Coding.system` value.
     *
     * An absent system is not a finding — the reference validator reports nothing for a Coding that
     * omits it, and several corpus documents do. The rules are mutually exclusive by construction:
     * a value carrying a `/ValueSet/` path segment necessarily also carries a scheme, so the elseif
     * is what keeps a document reporting one finding per Coding rather than two.
     */
    private function checkSystem(mixed $value, string $path): ?FHIRValidationViolation
    {
        $system = $this->readUri($value);

        if ($system === null || $system === '') {
            return null;
        }

        if (preg_match(self::SCHEME, $system) !== 1) {
            return $this->violation($path, 'Coding.system must be an absolute reference, not a local reference');
        }

        if (str_contains($system, '/ValueSet/')) {
            return $this->violation($path, sprintf("The Coding references a value set, not a code system ('%s')", $system));
        }

        return null;
    }

    /**
     * Read the lexeme out of whatever the deserializer put on the property.
     *
     * `system` is a `?UriPrimitive` in R4, R4B and R5, but the wrapper is \Stringable and its own
     * `value` may be absent (an extension-only element), which stringifies to '' and is absence,
     * not a malformed URI.
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

    /**
     * Reads the concrete class only, which is what the raw reflection did. Three classes carry
     * `#[FHIRComplexType(typeName: 'Coding')]` -- one per FHIR version -- and none of them is
     * extended anywhere in the generated or CDA models, so an ancestor walk would widen the match
     * for nothing.
     */
    private function isCoding(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isCoding[$key])) {
            return $this->isCoding[$key];
        }

        $attributes = $this->attributes->classAttributes($node, FHIRComplexType::class);

        return $this->isCoding[$key] = $attributes !== [] && $attributes[0]->typeName === self::CODING;
    }
}
