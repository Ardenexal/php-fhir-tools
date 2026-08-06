<?php

declare(strict_types=1);

namespace App\Sdc;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * Bidirectional mapping between a Questionnaire item's flat form value(s) and a
 * QuestionnaireResponse.item.answer `value[x]`. Used by both {@see QuestionnaireResponseBuilder}
 * (form -> QR, for $extract) and {@see QuestionnaireFormRenderer} (QR -> form, for $populate prefill)
 * so the two directions share one mapping and cannot drift apart.
 *
 * Supported `item.type` values: string, text, boolean, integer, decimal, date, dateTime, choice
 * (answerOption only), quantity. `display` carries no answer and is not routed through this codec at all.
 *
 * A raw PHP string assigned to `QuestionnaireResponseItemAnswer::$value` serializes as `valueDecimal`
 * (its `string`-phpType choice variant) — NOT `valueString`. Wrapping in `StringPrimitive` is what forces
 * `valueString`. See `PopulateModelFactory::stringValue()` for the same rule applied on the populate side.
 */
final class QuestionnaireItemCodec
{
    /** A three-state boolean form value: unanswered items must produce no answer, not `false`. */
    public const string BOOLEAN_TRUE  = 'true';

    public const string BOOLEAN_FALSE = 'false';

    /**
     * Convert one posted form-field string into a `QuestionnaireResponseItemAnswer::$value`, or null
     * when the item was left unanswered (an empty string, or the boolean control's unset state).
     *
     * @param array<string, mixed> $item a single Questionnaire item (decoded JSON), not a group
     */
    public function fromFormValue(array $item, string $formValue): mixed
    {
        $type = (string) ($item['type'] ?? 'string');

        if ($type === 'boolean') {
            return match ($formValue) {
                self::BOOLEAN_TRUE  => true,
                self::BOOLEAN_FALSE => false,
                default             => null,
            };
        }

        if ($formValue === '') {
            return null;
        }

        return match ($type) {
            'integer'  => (int) $formValue,
            'decimal'  => $formValue, // raw string -> valueDecimal; do not wrap, do not cast to float.
            'date'     => new DatePrimitive(value: FHIRDate::parse($formValue)),
            'dateTime' => new DateTimePrimitive(value: FHIRDateTime::parse($this->normalizeDateTime($formValue))),
            'choice'   => $this->answerOptionValue($item, $formValue),
            'quantity' => $this->quantityValue($formValue),
            default    => new StringPrimitive(value: $formValue), // string, text
        };
    }

    /**
     * Convert an existing `QuestionnaireResponseItemAnswer::$value` (read from a populate result, or
     * echoed back from a prior submission) into the form-field string this codec's inverse expects.
     */
    public function toFormValue(array $item, mixed $answerValue): string
    {
        if ($answerValue === null) {
            return '';
        }

        $type = (string) ($item['type'] ?? 'string');

        if ($type === 'boolean') {
            if (!\is_bool($answerValue)) {
                return '';
            }

            return $answerValue ? self::BOOLEAN_TRUE : self::BOOLEAN_FALSE;
        }

        if ($type === 'choice') {
            return $this->answerOptionIndexFor($item, $answerValue) ?? '';
        }

        if ($type === 'quantity') {
            return $answerValue instanceof Quantity ? $this->quantityFormValue($answerValue) : '';
        }

        if (\is_scalar($answerValue)) {
            return (string) $answerValue;
        }

        if ($answerValue instanceof \Stringable) {
            return (string) $answerValue;
        }

        return '';
    }

    /**
     * `<input type="datetime-local">` emits e.g. `1990-05-12T13:45` (no seconds, no offset). FHIR
     * dateTime requires a timezone when a time is present; `FHIRDateTime::parse()` tolerates the
     * omission by assuming UTC (preserving the original string), but we normalize to a fully-specified
     * value here so extracted output stays spec-valid rather than merely non-throwing.
     */
    private function normalizeDateTime(string $value): string
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value) === 1) {
            return $value . ':00Z';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $value) === 1) {
            return $value . 'Z';
        }

        return $value;
    }

    /**
     * Parse a "70 kg"-style form value into a `Quantity` — `value` is a raw numeric-string (never cast
     * to float, matching the model's own `numeric-string` typing) and `unit` a raw string. Strict:
     * `quantity` is a complex item type per `AnswerValueCoercer` ("strict-by-source-datatype"), so a form
     * value with no leading number is dropped as unanswered rather than guessed at.
     */
    private function quantityValue(string $formValue): ?Quantity
    {
        if (preg_match('/^(-?\d+(?:\.\d+)?)\s*(.*)$/', trim($formValue), $matches) !== 1) {
            return null;
        }

        $unit = trim($matches[2]);

        return new Quantity(value: $matches[1], unit: $unit !== '' ? $unit : null);
    }

    /** Render a `Quantity` back to its "70 kg"-style form value for prefill. */
    private function quantityFormValue(Quantity $quantity): string
    {
        $value = $quantity->value ?? '';
        $unit  = $quantity->unit;
        $unit  = \is_string($unit) ? $unit : ($unit?->value ?? '');

        return trim($value . ($unit !== '' ? ' ' . $unit : ''));
    }

    /**
     * Resolve a submitted `answerOption` index (the form value) to that option's own answer datatype —
     * never a bare re-typed scalar. Supports `valueCoding` and `valueString` options; an out-of-range or
     * unsupported-shape index yields null (dropped by the caller as unanswered).
     *
     * An `answerValueSet`-bound item (no `answerOption` list — the options come from an external value
     * set the library has no `$expand` support for, per M04's assumption) instead treats the form value
     * as a free-text code, producing a bare `Coding` (code only, no system): the renderer shows this as a
     * text input with a "Check code" action rather than a populated dropdown.
     */
    private function answerOptionValue(array $item, string $formValue): mixed
    {
        $options = $item['answerOption'] ?? [];
        if (\is_array($options) && $options !== []) {
            if (!ctype_digit($formValue)) {
                return null;
            }

            $option = $options[(int) $formValue] ?? null;
            if (!\is_array($option)) {
                return null;
            }

            if (isset($option['valueCoding']) && \is_array($option['valueCoding'])) {
                $coding = $option['valueCoding'];

                return new Coding(
                    system: isset($coding['system']) ? new UriPrimitive(value: (string) $coding['system']) : null,
                    code: isset($coding['code']) ? new CodePrimitive(value: (string) $coding['code']) : null,
                    display: isset($coding['display']) ? (string) $coding['display'] : null,
                );
            }

            if (isset($option['valueString'])) {
                return new StringPrimitive(value: (string) $option['valueString']);
            }

            return null;
        }

        if (\is_string($item['answerValueSet'] ?? null) && $item['answerValueSet'] !== '') {
            return new Coding(code: new CodePrimitive(value: $formValue));
        }

        return null;
    }

    /**
     * Find which `answerOption` index produced the given answer value, for prefill. Matches by
     * `Coding.code` (system-qualified when both sides have one) or by raw string equality.
     *
     * For an `answerValueSet`-bound item (no `answerOption` list), returns the bare code string itself
     * rather than an index — that's what the free-text input's value should prefill to.
     */
    private function answerOptionIndexFor(array $item, mixed $answerValue): ?string
    {
        $options = $item['answerOption'] ?? [];
        if (!\is_array($options) || $options === []) {
            if (\is_string($item['answerValueSet'] ?? null) && $answerValue instanceof Coding) {
                return $answerValue->code?->value;
            }

            return null;
        }

        foreach ($options as $index => $option) {
            if (!\is_array($option)) {
                continue;
            }

            if ($answerValue instanceof Coding && isset($option['valueCoding']['code'])) {
                $answerCode = $answerValue->code?->value;
                if ($answerCode !== null && $answerCode === (string) $option['valueCoding']['code']) {
                    return (string) $index;
                }
            }

            if (\is_string($answerValue) && isset($option['valueString']) && $answerValue === (string) $option['valueString']) {
                return (string) $index;
            }

            if ($answerValue instanceof StringPrimitive && isset($option['valueString'])
                                                        && $answerValue->value === (string) $option['valueString']) {
                return (string) $index;
            }
        }

        return null;
    }
}
