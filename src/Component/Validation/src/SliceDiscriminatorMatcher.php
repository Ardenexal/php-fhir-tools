<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyMembershipClientInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Determines whether a single FHIR array item belongs to a named slice by evaluating
 * the slice's discriminators against the item.
 *
 * Discriminator types:
 *   - 'value'   — The item has a specific value at the given path (string-coerced for primitives;
 *                 a complex value matches when the item holds every element it states)
 *   - 'pattern' — The item contains a pattern at the given path (subset/recursive match)
 *   - 'exists'  — The discriminator value is a boolean: true means the path element must be
 *                 present (non-null), false means it must be absent (null). With no boolean, the
 *                 slice does not say which, and no item matches it
 *   - 'type'    — The FHIR type of the value at the path is one of the listed type codes
 *   - 'profile' — The value at the path conforms to one of the listed profiles, as the profile
 *                 check handed to the constructor answers
 *
 * The path `$this` is the item itself. A `value` or `pattern` discriminator that carries a
 * `valueSet` instead of a value matches an item whose code is in that value set, as the
 * terminology client answers.
 *
 * An item belongs to a slice when it satisfies every discriminator. Where one of them cannot be
 * answered (no terminology client, a server that gave no answer, or no profile check), the answer
 * is null: the item neither belongs to the slice nor is known to be outside it.
 *
 * @author Ardenexal
 */
final class SliceDiscriminatorMatcher
{
    /**
     * @param FHIRTerminologyClientInterface|null                       $terminologyClient Answers value set membership for binding discriminators; null or the null client answers nothing
     * @param (\Closure(object|array<mixed>, string): (bool|null))|null $conformsTo        Whether a value conforms to a profile URL, or null when that cannot be told
     */
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly ?FHIRTerminologyClientInterface $terminologyClient = null,
        private readonly ?\Closure $conformsTo = null,
    ) {
    }

    /**
     * This matcher, answering `profile` discriminators with $conformsTo.
     *
     * @param \Closure(object|array<mixed>, string): (bool|null) $conformsTo Whether a value conforms to a profile URL, or null when that cannot be told
     */
    public function withProfileCheck(\Closure $conformsTo): self
    {
        return new self($this->propertyAccessor, $this->terminologyClient, $conformsTo);
    }

    /**
     * Returns true if $item matches the given discriminator, and false otherwise, including when the
     * answer cannot be told.
     *
     * @param object|array<string, mixed> $item               The slice array item to check
     * @param string                      $discriminatorType  'value', 'pattern', 'exists', 'type' or 'profile'
     * @param string                      $discriminatorPath  Property path within the item (e.g. 'system'), or '$this'
     * @param mixed                       $discriminatorValue The value to match/compare against
     */
    public function matches(
        object|array $item,
        string $discriminatorType,
        string $discriminatorPath,
        mixed $discriminatorValue,
    ): bool {
        return $this->matchesDiscriminator($item, ['type' => $discriminatorType, 'path' => $discriminatorPath, 'value' => $discriminatorValue]) === true;
    }

    /**
     * Whether $item satisfies every discriminator: true when it does, false when it fails one, and
     * null when it fails none but at least one could not be answered.
     *
     * A discriminator with neither `value` nor `valueSet` is one the generator found nothing to
     * match on, so no item satisfies it; nor does any item satisfy an empty list.
     *
     * @param object|array<string, mixed>                                               $item
     * @param list<array{type: string, path: string, value?: mixed, valueSet?: string}> $discriminators
     */
    public function matchesAll(object|array $item, array $discriminators): ?bool
    {
        if ($discriminators === []) {
            return false;
        }

        $verdict = true;
        foreach ($discriminators as $discriminator) {
            $match = $this->matchesDiscriminator($item, $discriminator);

            if ($match === false) {
                return false;
            }

            if ($match === null) {
                $verdict = null;
            }
        }

        return $verdict;
    }

    /**
     * Whether this matcher can answer a discriminator at all: false for a value set with no
     * terminology client, or a profile with no profile check. A slice keyed by one never learns
     * which items are its own, so its minimum cannot be checked.
     *
     * @param array{type: string, path: string, value?: mixed, valueSet?: string} $discriminator
     */
    public function canAnswer(array $discriminator): bool
    {
        if (!array_key_exists('value', $discriminator)) {
            return !isset($discriminator['valueSet']) || $this->hasTerminologyClient();
        }

        return $discriminator['type'] !== 'profile' || $this->conformsTo !== null;
    }

    /**
     * @param object|array<string, mixed>                                         $item
     * @param array{type: string, path: string, value?: mixed, valueSet?: string} $discriminator
     */
    private function matchesDiscriminator(object|array $item, array $discriminator): ?bool
    {
        $path = $discriminator['path'];

        if (!array_key_exists('value', $discriminator)) {
            return isset($discriminator['valueSet']) && in_array($discriminator['type'], ['value', 'pattern'], true)
                ? $this->matchesValueSet($item, $path, $discriminator['valueSet'])
                : false;
        }

        $value = $discriminator['value'];

        return match ($discriminator['type']) {
            'value'   => $this->matchesValue($item, $path, $value),
            'pattern' => $this->matchesPattern($item, $path, $value),
            'exists'  => $this->matchesExists($item, $path, $value),
            'type'    => $this->matchesType($item, $path, $value),
            'profile' => $this->matchesProfile($item, $path, $value),
            default   => $this->unsupportedType($discriminator['type']),
        };
    }

    /**
     * Exact value match. FHIR primitive wrappers implementing Stringable are compared as strings.
     * Null item values do NOT match any non-null discriminator value.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesValue(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        // A fixed complex value, e.g. a slice's own fixed Coding on `$this`
        if (is_array($expected)) {
            return $this->matchesPattern($item, $path, $expected);
        }

        foreach ($this->readPathValues($item, $path) as $actual) {
            // Coerce both sides to string for FHIR primitives (UriPrimitive, StringPrimitive, etc.)
            if ($actual instanceof \Stringable || is_string($actual)) {
                if ((string) $actual === (string) $expected) {
                    return true;
                }

                continue;
            }

            if ($actual === $expected) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recursive subset match: every key in $expected must exist in $actual with equal values.
     * This is the same logic as FHIRPatternValueValidator::isSubset().
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesPattern(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        if (!is_array($expected)) {
            // Scalar pattern — fall back to value matching
            return $this->matchesValue($item, $path, $expected);
        }

        foreach ($this->readPathValues($item, $path) as $actual) {
            $actualArray = self::asArray($actual);

            if ($actualArray !== null && $this->isSubset($expected, $actualArray)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Exists discriminator: $expected is a boolean.
     * true  → path element must be present (non-null)
     * false → path element must be absent (null)
     *
     * Anything else, such as the null a slice gets when its definition states neither, matches
     * nothing. Reading null as "absent" made every item without the element match the slice, so a
     * slice capped at one item rejected any second item.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesExists(
        object|array $item,
        string $path,
        mixed $expected,
    ): bool {
        if (!is_bool($expected)) {
            return false;
        }

        $present = $this->readPathValues($item, $path) !== [];

        return $expected === $present;
    }

    /**
     * Type discriminator: the FHIR type of any value at the path is one of $expected, a type code or
     * a list of them. Null when none is, but the type of one cannot be read, such as a plain string.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesType(object|array $item, string $path, mixed $expected): ?bool
    {
        $types   = is_array($expected) ? $expected : [$expected];
        $verdict = false;

        foreach ($this->readPathValues($item, $path) as $actual) {
            $type = FHIRValueType::of($actual);
            if ($type === null) {
                $verdict = null;
            } elseif (in_array($type, $types, true)) {
                return true;
            }
        }

        return $verdict;
    }

    /**
     * Profile discriminator: a value at the path conforms to one of $expected, a profile URL or a list
     * of them. Null when no value is known to conform and the profile check could not answer for at
     * least one, or there is no profile check.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesProfile(object|array $item, string $path, mixed $expected): ?bool
    {
        $values = $this->readPathValues($item, $path);
        if ($values === []) {
            return false;
        }

        if ($this->conformsTo === null) {
            return null;
        }

        $verdict = false;
        foreach ($values as $actual) {
            if (!is_object($actual) && !is_array($actual)) {
                continue;
            }

            foreach (is_array($expected) ? $expected : [$expected] as $profileUrl) {
                $conforms = ($this->conformsTo)($actual, (string) $profileUrl);
                if ($conforms === true) {
                    return true;
                }

                if ($conforms === null) {
                    $verdict = null;
                }
            }
        }

        return $verdict;
    }

    /**
     * Binding discriminator: the code at the path is in the value set. A CodeableConcept matches when
     * any of its codings is a member, as a required binding reads it, and so does a list of codings. Null when no coding is a
     * member and the client could not answer for at least one, or there is no client.
     *
     * @param object|array<string, mixed> $item
     */
    private function matchesValueSet(object|array $item, string $path, string $valueSetUrl): ?bool
    {
        // A code is read with the coding around it, so its system goes with it: `coding.code` is
        // answered from each coding, `code` from the item itself
        if ($path === 'code' || str_ends_with($path, '.code')) {
            $path = $path === 'code' ? '$this' : substr($path, 0, -5);
        }

        $codings = [];
        foreach ($this->readPathValues($item, $path) as $actual) {
            $nested = (is_object($actual) || is_array($actual)) ? $this->readPathValues($actual, 'coding') : [];
            foreach ($nested !== [] ? $nested : [$actual] as $coding) {
                $codings[] = $coding;
            }
        }

        if ($codings === []) {
            return false;
        }

        if (!$this->hasTerminologyClient()) {
            return null;
        }

        $verdict = false;
        foreach ($codings as $coding) {
            [$system, $code] = $this->systemAndCode($coding);
            if ($code === null) {
                continue;
            }

            $member = $this->membership($valueSetUrl, $system, $code);
            if ($member === true) {
                return true;
            }

            if ($member === null) {
                $verdict = null;
            }
        }

        return $verdict;
    }

    /**
     * A coding's system and code, or a bare code with no system.
     *
     * @return array{string|null, string|null}
     */
    private function systemAndCode(mixed $coding): array
    {
        if (!is_object($coding) && !is_array($coding)) {
            return [null, self::scalar($coding)];
        }

        $system = self::scalar($this->readPathValues($coding, 'system')[0] ?? null);
        $code   = self::scalar($this->readPathValues($coding, 'code')[0] ?? null);

        // A bare code primitive: an object with no `code` of its own holds the code itself
        if ($code === null && $coding instanceof \Stringable) {
            $code = (string) $coding;
        }

        return [$system, $code];
    }

    private static function scalar(mixed $value): ?string
    {
        return match (true) {
            $value instanceof \BackedEnum                                    => (string) $value->value,
            is_string($value), $value instanceof \Stringable, is_int($value) => (string) $value,
            default                                                          => null,
        };
    }

    /**
     * The client's verdict on one code, or null when a client that can say so gave none.
     */
    private function membership(string $valueSetUrl, ?string $system, string $code): ?bool
    {
        $client = $this->terminologyClient;
        if ($client === null) {
            return null;
        }

        if ($client instanceof FHIRTerminologyMembershipClientInterface) {
            return $system === null ? $client->checkCode($valueSetUrl, $code) : $client->checkCoding($valueSetUrl, $system, $code);
        }

        return $system === null ? $client->validateCode($valueSetUrl, $code) : $client->validateCoding($valueSetUrl, $system, $code);
    }

    /**
     * The null client says yes to every code, which would put every item in the first binding slice,
     * and so does a cache wrapped around it.
     */
    private function hasTerminologyClient(): bool
    {
        return !NullFHIRTerminologyClient::isAbsent($this->terminologyClient);
    }

    /**
     * Returns false and raises a warning for a discriminator type FHIR does not define.
     */
    private function unsupportedType(string $type): bool
    {
        trigger_error(
            "SliceDiscriminatorMatcher: discriminator type '{$type}' is not supported. "
            . 'Slice items using this type will never match.',
            E_USER_WARNING,
        );

        return false;
    }

    /**
     * Every value reachable at $path, following repeating elements rather than stopping at them.
     *
     * A discriminator path is FHIRPath, not a PHP property path. `coding.code` means "the code of
     * each coding", and a slice matches when *any* occurrence satisfies the rule — the vital-signs
     * `VSCat` slice discriminates on exactly that path, against a `category` whose `coding` repeats.
     * PropertyAccessor has no such notion: handed `coding.code` with a list at `coding`, it throws
     * `NoSuchPropertyException`. That read as "no value", which read as "no match", which reported a
     * required slice missing on documents that plainly satisfied it. So the path is walked one
     * segment at a time and every repetition is followed. `$this` is the item itself.
     *
     * @param object|array<string, mixed> $item
     *
     * @return list<mixed> Non-null values found, empty when the path is unreachable
     */
    private function readPathValues(object|array $item, string $path): array
    {
        if ($path === '') {
            return [];
        }

        if ($path === '$this') {
            return [$item];
        }

        $current = [$item];

        foreach (explode('.', FHIRElementPath::toPropertyPath($path)) as $segment) {
            $next = [];

            foreach ($current as $node) {
                if (!is_object($node) && !is_array($node)) {
                    continue;
                }

                try {
                    $value = $this->propertyAccessor->getValue($node, $segment);
                } catch (\Throwable) {
                    continue;
                }

                foreach (is_array($value) ? $value : [$value] as $entry) {
                    if ($entry !== null) {
                        $next[] = $entry;
                    }
                }
            }

            $current = $next;
        }

        return $current;
    }

    /**
     * Recursively checks that all keys in $pattern are present in $value with matching values.
     *
     * @param array<string, mixed> $pattern
     * @param array<string, mixed> $value
     */
    private function isSubset(array $pattern, array $value): bool
    {
        foreach ($pattern as $key => $expected) {
            if (!array_key_exists($key, $value)) {
                return false;
            }

            if (is_array($expected)) {
                // A complex element the model holds as an object, such as an Identifier's `type`
                $actual = self::asArray($value[$key]);
                if ($actual === null) {
                    return false;
                }

                // For list-type arrays (e.g. coding[]), match if any element is a superset
                if (array_is_list($expected)) {
                    foreach ($expected as $expectedItem) {
                        $found = false;
                        foreach ($actual as $actualItem) {
                            $actualItem = self::asArray($actualItem);
                            if ($actualItem !== null && $this->isSubset((array) $expectedItem, $actualItem)) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            return false;
                        }
                    }
                } elseif (!$this->isSubset($expected, $actual)) {
                    return false;
                }
            } elseif ((!is_scalar($value[$key]) && !$value[$key] instanceof \Stringable) || (string) $value[$key] !== (string) $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * A complex value as an array of its elements: an array as it is, an object by its public
     * properties (or its JSON form when it has one), anything else null.
     *
     * @return array<mixed>|null
     */
    private static function asArray(mixed $value): ?array
    {
        return match (true) {
            is_array($value)                     => $value,
            $value instanceof \JsonSerializable  => (array) $value->jsonSerialize(),
            is_object($value)                    => get_object_vars($value),
            default                              => null,
        };
    }
}
