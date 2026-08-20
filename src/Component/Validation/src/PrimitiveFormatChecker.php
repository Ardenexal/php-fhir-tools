<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

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
 * ## What is deliberately not checked
 *
 * Only temporal lexemes that failed to parse. Whitespace, code, oid, id and uri shape rules are each
 * a separate capability with its own oracle evidence — and Java reports "Primitive types should not
 * only be whitespace" as a *warning*, so guessing at severity here would turn an agreeing case into
 * a disagreeing one. Values that parse are left alone even when the canonical FHIR regex would
 * reject them (`0000-01-01T12:32:45Z`, `1983-01-01T12:32:45-15:00`): brick/date-time is more lenient
 * than the spec regex, and closing that gap moves no case out of BELOW while widening the surface
 * across every readable document.
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
        $violations   = [];
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
            return null;
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
     * A decimal lexeme is kept verbatim, so the canonical regex can be applied to it directly.
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

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: sprintf("Element value '%s' does not meet decimal regex '%s'", $value, self::DECIMAL_SOURCE),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
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
