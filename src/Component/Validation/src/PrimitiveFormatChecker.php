<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;

/**
 * Reports primitive values the deserializer read but could not interpret.
 *
 * ## Why this is not a Symfony constraint
 *
 * The generated primitive classes carry `Regex` constraints, but nothing reaches them: no
 * `Assert\Valid` cascades into primitive-typed properties. That cascade is still absent for the
 * reasons set out in the nested-cascade work — `boolean`, `integer` and `decimal` choice variants are
 * generated as bare PHP scalars, so no primitive object exists on those properties to descend into —
 * not because any emitted pattern is defective. R5 `decimal`'s stray-brace defect, which this
 * docblock previously cited as the blocker, is corrected at the generator
 * (`FHIRModelGenerator::REGEX_CORRECTIONS`).
 *
 * This pass walks the tree itself, so the rule holds whether or not the cascade is ever switched on.
 *
 * ## Why the deserializer no longer aborts
 *
 * Malformed primitive syntax is a validation finding, not a reason to refuse a document. The
 * reference validator reads `primitive-bad.xml` end to end and reports forty located errors; we
 * aborted on the first bad temporal and reported none of them. {@see FHIRTemporalValue::unparsed()}
 * retains the lexeme as written, and this pass is what turns it back into a finding.
 *
 * ## What is checked
 *
 * Temporal lexemes that failed to parse, decimal lexemes against the canonical regex, and the shape
 * of `code`, `id`, `oid`, `uri` and `base64Binary` values that arrived intact. The shape rules were
 * added once the corpus was measured finding by finding rather than case by case; each carries the
 * reference validator's exact wording, because a rule whose text does not match cannot pair.
 *
 * ## What is deliberately not checked
 *
 * **Whitespace as an error.** The reference validator reports "Primitive types should not only be
 * whitespace" and "value should not start or finish with whitespace" as *warnings*. Warning parity is
 * out of scope by decision, so emitting these as errors would turn an agreeing case into a
 * disagreeing one. `base64Binary` is the trap: R5 says "are not allowed to contain any whitespace"
 * as an error while R4 says "SHOULD not contain" as a warning, so that rule is version-gated.
 *
 * **Temporal values that parse.** `0000-01-01T12:32:45Z` and `1983-01-01T12:32:45-15:00` are read
 * successfully by brick/date-time, which is more lenient than the canonical FHIR regex, so
 * {@see self::checkTemporal()} sees nothing wrong with them. They are still two reference findings.
 *
 * This docblock previously justified skipping them on the grounds that closing the gap "moves no case
 * out of BELOW" — true under case counting, and false under finding counting, which is how the gap is
 * now measured. The exclusion stands only because the rule is not written yet, not because it is free.
 * Do not restore the old justification.
 *
 * **`boolean` and `integer` of any shape.** Not a decision so much as a consequence: those types are
 * generated as bare PHP scalars, so `TRUE`, `1` and `yes` all reach the model as `false`, and
 * `34534536346345345345` arrives saturated at `PHP_INT_MAX`. The malformed text is gone before any
 * rule could see it. Reporting them needs the lexeme retained at the reader, the way
 * {@see FHIRTemporalValue::unparsed()} retains a temporal one.
 */
final class PrimitiveFormatChecker
{
    /**
     * Java's wording per temporal type, keyed by the value object the deserializer produced.
     *
     * `dateTime` is reported as "date/time", matching the reference validator. Its message for an
     * unreadable `time` is `Not a valid time (null)` — an artifact of losing the lexeme, which is
     * exactly what this design keeps — so the lexeme is named here instead.
     *
     * @var array<class-string<FHIRTemporalValue>, string>
     */
    private const array MALFORMED = [
        FHIRDate::class     => "Not a valid date format: '%s'",
        FHIRDateTime::class => "Not a valid date/time format: '%s'",
        FHIRInstant::class  => "Not a valid instant format: '%s'",
        FHIRTime::class     => "Not a valid time format: '%s'",
    ];

    /**
     * A seconds field of 60 — a leap second, which FHIR permits and brick/date-time cannot parse.
     *
     * Anchored on the seconds position specifically, so `11:60:59` (minute 60, genuinely invalid and
     * reported by the oracle) is not caught by it.
     */
    private const string LEAP_SECOND = '/(?:^|T)\d{2}:\d{2}:60(?:\.\d+)?(?:Z|[+\-]\d{2}:\d{2})?$/';

    /**
     * The canonical FHIR `decimal` pattern, quoted in the message exactly as the reference validator
     * writes it — see `outcomes/java` (search: `does not meet decimal regex`).
     *
     * Not the same artifact as the generated `DecimalPrimitive` Regex constraint, and deliberately
     * still written out here: this string is *message text* as much as it is a rule, and reading it
     * off a generated attribute would couple our wording to codegen. The two must nonetheless agree,
     * which `GeneratedPrimitiveRegexTest` pins
     * (search: `testGeneratedDecimalPatternAgreesWithPrimitiveFormatChecker`).
     *
     * Public for that test alone. It is the one constant here that another component has a legitimate
     * reason to read, precisely because drift between the two decimal rules is the failure mode.
     *
     * Note it is stricter than R5's published regex in one respect beyond the stray brace the
     * generator now corrects: the exponent may not carry a leading zero. That follows Java, which
     * flags `1e09` on `R5.primitive-good`.
     */
    public const string DECIMAL_SOURCE = '-?(0|[1-9][0-9]{0,17})(\.[0-9]{1,17})?([eE](0|[+\-]?[1-9][0-9]{0,9}))?';

    private const string DECIMAL = '/\A(?:' . self::DECIMAL_SOURCE . ')\z/';

    /**
     * The canonical FHIR `instant` pattern, quoted in the message exactly as the reference validator
     * writes it — see `outcomes/java` (search: `does not meet instant regex`).
     *
     * The year alternation excludes `0000`, and the offset alternation caps at `14:00`; those two are
     * the only parts the corpus actually exercises.
     */
    public const string INSTANT_SOURCE = '([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?(Z|(\+|-)((0[0-9]|1[0-3]):[0-5][0-9]|14:00))';

    private const string INSTANT = '/\A(?:' . self::INSTANT_SOURCE . ')\z/';

    /**
     * {@see self::INSTANT_SOURCE} with the offset relaxed to any two-digit pair.
     *
     * Used only to tell "the offset is out of range" from "something else is wrong", because the
     * reference validator words those two differently.
     */
    private const string INSTANT_ANY_OFFSET = '/\A([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?(Z|(\+|-)\d{2}:\d{2})\z/';

    /**
     * A well-formed number in canonical form: no leading zeros, no trailing dot, no stray characters.
     *
     * Deliberately looser than {@see self::DECIMAL} on precision and exponent size — it exists only to
     * separate "not a number" from "a number FHIR will not accept", which is the difference between the
     * reference validator's two decimal messages.
     */
    private const string CANONICAL_NUMBER = '/\A-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][+\-]?[0-9]+)?\z/';

    /**
     * Every Unicode separator plus ASCII whitespace.
     *
     * `\s` under `/u` does not match U+00A0, which `R5.cs-v2-0550` needs, so `\p{Z}` is included.
     */
    private const string WHITESPACE_CLASS = '[\p{Z}\s]';

    private const string URI_WHITESPACE = '/' . self::WHITESPACE_CLASS . '/u';

    /** ASCII whitespace only; the base64 rule does not use {@see self::WHITESPACE_CLASS}. */
    private const string BASE64_WHITESPACE = '/\s/u';

    private const string ID = '/\A[A-Za-z0-9\-\.]{1,64}\z/';

    /**
     * An OID's node sequence, matched after the `urn:oid:` prefix has been stripped.
     */
    private const string OID = '/\A[0-2](\.(0|[1-9][0-9]*))+\z/';

    private const string TIMEZONE_SUFFIX = '/(Z|[+\-]\d{2}:\d{2})\z/';

    /**
     * A timezone FHIR actually permits: `Z`, or an offset within ±14:00.
     *
     * {@see TIMEZONE_SUFFIX} asks only whether a suffix is present, which is the wrong question once the
     * value has parsed — brick accepts offsets the specification does not, so `-15:00` reads fine. The
     * offset alternation here is the same one {@see INSTANT_SOURCE} carries, kept as its own constant so
     * the `dateTime` branch can reuse it without relaxing the whole instant pattern.
     */
    private const string VALID_TIMEZONE_SUFFIX = '/(Z|(\+|-)((0[0-9]|1[0-3]):[0-5][0-9]|14:00))\z/';

    private const string URI_SCHEME = '/\A[a-zA-Z][a-zA-Z0-9+.\-]*:/';

    /** @var array<string, bool> keyed by declaring class and property name; reflection is not free */
    private array $decimalProperties = [];

    /**
     * Walk a resource and report every primitive value that could not be read.
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
        $violations   = $this->checkShape($node, $path);
        $ref          = new \ReflectionClass($node);

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($prop->isInitialized($node) === false) {
                continue;
            }

            $value   = $prop->getValue($node);
            $subPath = $path === '' ? $prop->getName() : $path . '.' . $prop->getName();

            if ($value instanceof FHIRTemporalValue) {
                // A temporal value object is the `value` slot of a primitive wrapper, so the wrapper's
                // own path is the element the reference validator names — `parameter[9].value`, not
                // `parameter[9].value.value`.
                $violation = $this->checkTemporal($value, $prop->getName() === 'value' && $path !== '' ? $path : $subPath);

                if ($violation !== null) {
                    $violations[] = $violation;
                }

                continue;
            }

            if (is_string($value) && $value !== '' && $this->isDecimal($prop)) {
                $violation = $this->checkDecimal($value, $subPath);

                if ($violation !== null) {
                    $violations[] = $violation;
                }

                continue;
            }

            if ($value === '' && $this->isEmptyReportableScalar($prop)) {
                $violations[] = $this->violation($subPath, 'value cannot be empty');

                continue;
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
     * A value that parsed is not a finding; only a retained lexeme is.
     *
     * A null value is not a finding either — `<valueDate><extension url="…data-absent-reason"/></valueDate>`
     * builds a wrapper whose value is absent, which the reference validator reports nothing about —
     * and such a wrapper never holds a temporal value object at all, so it cannot reach here.
     *
     * The exception is a leap second. FHIR's seconds field is `([0-5][0-9]|60)`, so `…T12:59:60+10:00`
     * is a legal lexeme the reference validator accepts; brick/date-time simply cannot represent it.
     * Reporting it would blame the document for our own limitation — and `primitive-good`, a document
     * the oracle passes on every temporal, is exactly where that shows up.
     */
    private function checkTemporal(FHIRTemporalValue $value, string $path): ?FHIRValidationViolation
    {
        if ($value->getParseError() === null) {
            return $this->checkParsedTemporal($value, $path);
        }

        if (preg_match(self::LEAP_SECOND, (string) $value) === 1) {
            return null;
        }

        $template = self::MALFORMED[$value::class] ?? "Not a valid format: '%s'";

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: sprintf($template, (string) $value),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Report a primitive whose lexeme is legal text but not a legal value of its own type.
     *
     * These rules are separate from the temporal and decimal passes because nothing failed to parse:
     * the value arrived intact and is simply not what its type permits. The discriminator is the
     * class-level {@see FHIRPrimitive} attribute, which names the FHIR type directly — a subclass
     * relationship cannot be used for it, because `OidPrimitive extends UriPrimitive` while the two
     * carry different rules.
     *
     * Each message is the reference validator's wording character for character, including the
     * missing space in `whitespace('…')` and the parenthesised remainder in `OIDs must be valid (…)`.
     * A rule whose text does not match cannot pair, so a "tidier" message is a broken one.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkShape(object $node, string $path): array
    {
        $attributes = (new \ReflectionClass($node))->getAttributes(FHIRPrimitive::class);

        if ($attributes === [] || $path === '') {
            return [];
        }

        $meta = $attributes[0]->newInstance();
        /** @var mixed $value */
        $value = $node->value ?? null;

        if (!is_string($value)) {
            return [];
        }

        if ($value === '') {
            return $this->checkEmpty($node, $path);
        }

        return match ($meta->primitiveType) {
            'base64Binary' => $this->checkBase64($value, $path, $meta->fhirVersion),
            'code'         => $this->checkCode($value, $path),
            'id'           => $this->checkId($value, $path),
            'oid'          => $this->checkOid($value, $path),
            'uri'          => $this->checkUriWhitespace($value, $path),
            'canonical'    => $this->checkCanonical($value, $path),
            default        => [],
        };
    }

    /**
     * Base64 content, and whitespace inside it.
     *
     * Whitespace is reported alone: on `R5.base64-whitespace` the reference validator reports the
     * whitespace and stops, rather than also calling the value malformed.
     *
     * ## Length only, deliberately — the alphabet is not checked
     *
     * A charset check looks obviously right and is wrong. The reference validator accepts
     * `MEKH....SD/Z` on `R5.narrative-binary`, dots and all, and reporting it cost two ABOVE cases
     * the first time this rule was written. Every value the oracle judges is explained by length
     * alone:
     *
     * | Value | Length | Oracle |
     * |---|---:|---|
     * | `MEKH....SD/Z` | 12 | accepted |
     * | `YXNhcs2Rhc2Q=` | 13 | rejected |
     * | `%%%2@()()` | 9 | rejected |
     * | `(snip)` | 6 | rejected |
     *
     * So the rule is a multiple-of-four test and nothing more. Do not "tighten" it to the base64
     * alphabet without a corpus case that demands it; the corpus currently demands the opposite.
     *
     * The severity is version-dependent, and this is the one rule here where that matters. R5 says
     * "are not allowed to contain any whitespace" as an **error**; R4 says "SHOULD not contain" as a
     * **warning**. Warning parity is out of scope for this work by decision, so R4 emits nothing —
     * emitting an error there would turn an agreeing case into a disagreeing one, which is the exact
     * failure this milestone must not cause.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkBase64(string $value, string $path, string $fhirVersion): array
    {
        if (preg_match(self::BASE64_WHITESPACE, $value) === 1) {
            if ($fhirVersion !== 'R5') {
                return [];
            }

            return [$this->violation(
                $path,
                'Base64 encoded values are not allowed to contain any whitespace (per RFC 4648). '
                . 'Note that non-validating readers are encouraged to accept whitespace anyway',
            )];
        }

        if (strlen($value) % 4 === 0) {
            return [];
        }

        return [$this->violation($path, sprintf("The value '%s' is not a valid Base64 value", $value))];
    }

    /**
     * `code` forbids leading and trailing whitespace and runs of more than one space.
     *
     * Deliberately narrower than the canonical `[^\s]+( [^\s]+)*` regex. `code` values appear on
     * almost every resource in the corpus, so a rule derived from the regex alone would report on
     * documents the reference validator passes, and over-reporting outranks closing this gap. Only
     * the shapes the oracle actually flags are reported: `' asdasd'`, `'asd  asd'`, `'asdasd '` and
     * `'CHEST\u{A0}'`.
     *
     * `\s` under `/u` does not match U+00A0, which `R5.cs-v2-0550` needs, so the class is widened to
     * `\p{Z}` — every Unicode separator — plus the ASCII controls.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkCode(string $value, string $path): array
    {
        $ws = self::WHITESPACE_CLASS;

        $offends = preg_match('/\A' . $ws . '/u', $value) === 1
            || preg_match('/' . $ws . '\z/u', $value)     === 1
            || preg_match('/' . $ws . '{2,}/u', $value)   === 1
            || preg_match('/[\t\r\n]/', $value)           === 1;

        if (!$offends) {
            return [];
        }

        return [$this->violation($path, sprintf("The code '%s' is not valid (whitespace rules)", $value))];
    }

    /**
     * `id` permits letters, digits, hyphen and dot, up to 64 characters.
     *
     * This is the same shape `AbstractResource::$id` enforces, but never the same value: a resource's
     * own `id` is a plain string property there, not an `IdPrimitive`, so the two rules cannot both
     * fire on one element and the reference validator's `Invalid Resource id:` findings — which M09
     * paired — are untouched by this.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkId(string $value, string $path): array
    {
        if (preg_match(self::ID, $value) === 1) {
            return [];
        }

        return [$this->violation($path, sprintf("id value '%s' is not valid", $value))];
    }

    /**
     * An `oid` is a `urn:oid:`-prefixed OID, and carries the `uri` whitespace rule as well.
     *
     * The reference validator reports up to two findings on one value, and the combinations are
     * specific rather than cumulative — `R5.primitive-bad` pins all four:
     *
     * - `oid:0.1.2.3`      -> cannot start with `oid:`, *and* must start with `urn:oid:` (2 findings)
     * - `urn:oid: 0.1.2.3` -> uri whitespace, *and* must be valid ` 0.1.2.3` (2 findings)
     * - `urn:oid:a0.1.2.3` -> must be valid `a0.1.2.3` (1 finding)
     * - `0.1.2.3`          -> must start with `urn:oid:` (1 finding)
     *
     * So a value that already fails the prefix is not also called an invalid OID; the digit shape is
     * only judged once the prefix is right.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkOid(string $value, string $path): array
    {
        $violations = $this->checkUriWhitespace($value, $path);

        if (str_starts_with($value, 'oid:')) {
            $violations[] = $this->violation($path, 'URI values cannot start with oid:');
            $violations[] = $this->violation($path, 'OIDs must start with urn:oid:');

            return $violations;
        }

        if (!str_starts_with($value, 'urn:oid:')) {
            $violations[] = $this->violation($path, 'OIDs must start with urn:oid:');

            return $violations;
        }

        $oid = substr($value, strlen('urn:oid:'));

        if (preg_match(self::OID, $oid) !== 1) {
            $violations[] = $this->violation($path, sprintf('OIDs must be valid (%s)', $oid));
        }

        return $violations;
    }

    /**
     * A URI may not contain whitespace. Note the reference validator's missing space before `(`.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkUriWhitespace(string $value, string $path): array
    {
        if (preg_match(self::URI_WHITESPACE, $value) !== 1) {
            return [];
        }

        return [$this->violation($path, sprintf("URI values cannot have whitespace('%s')", $value))];
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
     * An element that is present but carries no value, and no extensions to stand in for one.
     *
     * The extension test is the whole rule. An empty value is how a primitive records "this element
     * was present, and its content lives in an extension" — `<valueDate><extension
     * url="…data-absent-reason"/></valueDate>` — which the reference validator says nothing about.
     * Reporting that would blame a document for using extensions correctly.
     *
     * ## Why an element inside a repeating property is skipped
     *
     * Not a rule of FHIR, but a guard against a defect of ours. `Meta.profile` deserializes to **two**
     * entries from `R4.demo-example-1`, which has exactly one `<profile>` element in its XML; the
     * second is a phantom `CanonicalPrimitive` with an empty value. Reporting it cost three ABOVE
     * cases, and the document is not at fault — our reader is.
     *
     * Every one of the five findings this rule exists for sits at a non-repeating element
     * (`extension[0].url`, `entry[0].fullUrl`, and three `parameter[n].value` slots), so restricting it
     * costs no coverage against the oracle. The restriction should be lifted only once the phantom is
     * fixed at the reader — the defect is recorded in `backlog.md`, not worked around here, because a
     * validator that reports its own reader's mistakes is worse than one that stays quiet.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkEmpty(object $node, string $path): array
    {
        if (str_ends_with($path, ']')) {
            return [];
        }

        /** @var mixed $extensions */
        $extensions = $node->extension ?? [];

        if (is_array($extensions) && $extensions !== []) {
            return [];
        }

        return [$this->violation($path, 'value cannot be empty')];
    }

    /**
     * Does an empty string on this plain scalar property mean "present but empty"?
     *
     * Two shapes carry `value cannot be empty`, and only one of them is a primitive wrapper. The other
     * is a plain string property — `Patient.extension[0].url` on `R4.patient-extension-bad2` and
     * `Bundle.entry[0].fullUrl` on `R5.bundle-bad-empty` — where there is no wrapper to inspect.
     *
     * Two exclusions keep this from firing where the reference validator is silent:
     *
     * - A property declared *inside* a primitive wrapper is already covered by {@see self::checkEmpty()},
     *   so counting it here would report the same element twice.
     * - A choice property is skipped entirely. An empty string on one of those is how an
     *   extension-only choice scalar records that its element was present — the same convention
     *   {@see self::checkDecimal()} relies on — so it means absence, not emptiness.
     * - A `decimal` property is skipped for that same reason even when it is not a choice.
     *   `Quantity.value` is the case: an empty string there is extension-only presence, and reporting
     *   it broke `PrimitiveFormatCheckerTest::testEmptyDecimalIsNotReported`, which exists to pin
     *   exactly this. Both findings this rule is for sit on `uri` scalars — `extension[0].url` and
     *   `entry[0].fullUrl` — so nothing is lost by leaving numbers alone.
     */
    private function isEmptyReportableScalar(\ReflectionProperty $prop): bool
    {
        if ($prop->getDeclaringClass()->getAttributes(FHIRPrimitive::class) !== []) {
            return false;
        }

        $attributes = $prop->getAttributes(FhirProperty::class);

        if ($attributes === []) {
            return false;
        }

        $meta = $attributes[0]->newInstance();

        if (($meta->variants ?? []) !== []) {
            return false;
        }

        if ($this->isDecimal($prop)) {
            return false;
        }

        return $meta->propertyKind === 'scalar';
    }

    /**
     * A temporal value that parsed cleanly, but is still not a legal FHIR lexeme.
     *
     * brick/date-time is more permissive than the specification, so parsing succeeding proves very
     * little: `0000-01-01T12:32:45Z`, `1983-01-01T12:32:45-15:00` and `2020-11-11T10:58:14.768528` all
     * read fine and are all reported by the reference validator.
     *
     * The instant rules come first because `instant` always requires a timezone, so its regex covers
     * the timezone case already; `dateTime` requires one only when a time is actually present, which is
     * why the check is conditional on `T` rather than applied to every date.
     */
    private function checkParsedTemporal(FHIRTemporalValue $value, string $path): ?FHIRValidationViolation
    {
        $lexeme = (string) $value;

        if ($value instanceof FHIRInstant) {
            if (preg_match(self::INSTANT, $lexeme) === 1) {
                return null;
            }

            // Both messages exist and are not interchangeable. An offset outside ±14:00 is reported as
            // an unreadable instant; anything else failing the regex is reported against the regex.
            // Relaxing only the offset separates the two without guessing.
            if (preg_match(self::INSTANT_ANY_OFFSET, $lexeme) === 1) {
                return $this->violation($path, sprintf("Not a valid instant format: '%s'", $lexeme));
            }

            return $this->violation(
                $path,
                sprintf("Element value '%s' does not meet instant regex '%s'", $lexeme, self::INSTANT_SOURCE),
            );
        }

        if (!$value instanceof FHIRDateTime || !str_contains($lexeme, 'T')) {
            return null;
        }

        // A timezone is present and inside the range FHIR permits, so the value is well formed.
        if (preg_match(self::VALID_TIMEZONE_SUFFIX, $lexeme) === 1) {
            return null;
        }

        // A suffix that is present but outside ±14:00 is a malformed dateTime, not a missing timezone.
        // Checking only for presence let `1983-01-01T12:32:45-15:00` through while the identical lexeme
        // typed `instant` was rejected by the branch above, so one type enforced the offset range and the
        // other did not. The wording is the one {@see MALFORMED} already uses for an unparseable
        // dateTime, because the reference validator does not distinguish the two either.
        if (preg_match(self::TIMEZONE_SUFFIX, $lexeme) === 1) {
            return $this->violation($path, sprintf(self::MALFORMED[FHIRDateTime::class], $lexeme));
        }

        return $this->violation($path, 'If a date has a time, it must have a timezone');
    }

    /**
     * A `canonical` must be absolute, unless it is a fragment reference.
     *
     * `Library/library-cms146-example` on `R4.mr-m-simple-nossystem` is the shape this catches: a
     * relative reference where a canonical URL is required. A leading `#` is a fragment and legal, and
     * anything carrying a scheme — including `urn:` — is absolute and legal.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkCanonical(string $value, string $path): array
    {
        $violations = $this->checkUriWhitespace($value, $path);

        if (str_starts_with($value, '#') || preg_match(self::URI_SCHEME, $value) === 1) {
            return $violations;
        }

        $violations[] = $this->violation(
            $path,
            sprintf('Canonical URLs must be absolute URLs if they are not fragment references (%s)', $value),
        );

        return $violations;
    }

    /**
     * A decimal lexeme is kept verbatim, so the canonical regex can be applied to it directly.
     *
     * ## Two messages, because the reference validator has two
     *
     * Both mean "this is not a legal FHIR decimal", and they are not interchangeable:
     *
     * - `The value '00.1' is not a valid decimal` — the lexeme is not a number. A leading zero, a
     *   trailing dot, anything unparseable. `R5.primitive-bad` parameter[6].
     * - `Element value '1000000000000000000' does not meet decimal regex '…'` — the lexeme *is* a
     *   well-formed number, and breaks the regex's 17-digit precision or exponent cap.
     *   `R5.obs-decimal` component[4], which already pairs on this wording.
     *
     * Emitting one wording for both cases leaves the other unpaired, which is how parameter[6] came to
     * be counted as a missing check while we were already reporting on that element. Note this only
     * ever changes the *message*, never the number of findings.
     *
     * The empty string never reaches here: it is how an extension-only choice scalar records that
     * its element was present (see the guards in the XML normalizers), which is absence, not a
     * malformed number.
     */
    private function checkDecimal(string $value, string $path): ?FHIRValidationViolation
    {
        if (preg_match(self::DECIMAL, $value) === 1) {
            return null;
        }

        // A lexeme that is not a number at all is reported as such; one that is a number but breaks
        // the canonical regex's precision or exponent caps is reported against the regex.
        if (preg_match(self::CANONICAL_NUMBER, $value) !== 1) {
            return $this->violation($path, sprintf("The value '%s' is not a valid decimal", $value));
        }

        return $this->violation(
            $path,
            sprintf("Element value '%s' does not meet decimal regex '%s'", $value, self::DECIMAL_SOURCE),
        );
    }

    /**
     * Does this property hold a `decimal` lexeme?
     *
     * Decimals reach the model as PHP strings in three shapes: a plain scalar property
     * (`Quantity.value`), the `value` slot of `DecimalPrimitive`, and a choice variant. The choice
     * case is unambiguous because `decimal` is the only variant the generator maps to a scalar
     * `string` — `boolean` maps to `bool`, `integer` to `int`, and every other variant to an object
     * — so a string sitting on a choice property with a decimal variant is that variant.
     */
    private function isDecimal(\ReflectionProperty $prop): bool
    {
        $key = $prop->getDeclaringClass()->getName() . '::' . $prop->getName();

        if (isset($this->decimalProperties[$key])) {
            return $this->decimalProperties[$key];
        }

        $isDecimal  = false;
        $attributes = $prop->getAttributes(FhirProperty::class);

        if ($attributes !== []) {
            $meta = $attributes[0]->newInstance();

            $isDecimal = in_array($meta->fhirType, ['decimal', 'http://hl7.org/fhirpath/System.Decimal'], true);

            foreach ($meta->variants ?? [] as $variant) {
                if ($variant['fhirType'] === 'decimal' && $variant['phpType'] === 'string') {
                    $isDecimal = true;
                    break;
                }
            }
        }

        return $this->decimalProperties[$key] = $isDecimal;
    }
}
