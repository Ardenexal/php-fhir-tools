<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Populate;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;

/**
 * Coerces an expression result to the FHIR datatype an answer of a given `Questionnaire.item.type`
 * requires, emitting an `OperationOutcome` issue (via the supplied {@see PopulateModelFactory}) for every
 * empty, mismatched, or unsupported case rather than dropping a value silently.
 *
 * Strict-by-source-datatype for complex items (`choice`/`quantity`/`reference`/`attachment`): the
 * expression must already resolve to the target datatype object; a bare scalar is a mismatch, never a
 * silent coercion. Extracted from {@see FHIRQuestionnairePopulateService}; behaviour is unchanged.
 *
 * @internal implementation detail of the `Sdc` population path; not part of the public API
 */
final class AnswerValueCoercer
{
    /**
     * @param FhirPrimitiveReader $primitives reads primitive-wrapper-or-scalar values off deserializer-origin
     *                                        models; shared with the populate service so both read primitives identically
     */
    public function __construct(
        private readonly FhirPrimitiveReader $primitives = new FhirPrimitiveReader(),
    ) {
    }

    /**
     * Coerce an evaluated expression result to the answer value for an item of type `$itemType`, or null
     * (with an issue recorded) when the value is empty, mismatched, or the type is unsupported.
     *
     * @param list<object> $issues
     */
    public function coerce(?string $itemType, mixed $value, string $linkId, PopulateModelFactory $factory, array &$issues): mixed
    {
        $scalar = $this->primitives->stringify($value);

        // An item with no declared type has no coercion target; handling it here also narrows $itemType to a
        // non-null string for every case below (so the temporal branch can pass it as a plain string).
        if ($itemType === null) {
            return $this->unsupportedTypeIssue(null, $linkId, $factory, $issues);
        }

        switch ($itemType) {
            case 'string':
            case 'text':
                if ($this->primitives->isEmptyString($value)) {
                    // A FHIR `string` must be non-empty (min length 1), so an empty-string result is not a
                    // type mismatch — it is simply "not answered". Emit an information issue (parity with the
                    // empty-`initialExpression` branch), never a misleading incompatible-type warning.
                    $issues[] = $factory->issue(
                        IssueSeverity::information->value,
                        IssueType::informationalnote->value,
                        \sprintf("initialExpression for item '%s' produced an empty string; item left unanswered.", $linkId),
                    );

                    return null;
                }

                return $scalar !== null ? $factory->stringValue($scalar) : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'url':
                return $scalar !== null ? $factory->uriValue($scalar) : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'boolean':
                return \is_bool($value) ? $value : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'integer':
                if (\is_int($value)) {
                    return $value;
                }

                return \is_string($scalar) && $this->primitives->isIntegerString($scalar) ? (int) $scalar : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'decimal':
                // The `decimal` answer variant is a raw string (→ `valueDecimal`), distinct from the
                // `StringPrimitive` `string` variant (→ `valueString`).
                return $scalar !== null && is_numeric($scalar) ? $scalar : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'date':
            case 'dateTime':
            case 'time':
                return $scalar !== null
                    ? $this->coerceTemporal($itemType, $scalar, $linkId, $factory, $issues)
                    : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'choice':
            case 'open-choice':
            case 'quantity':
            case 'reference':
            case 'attachment':
                // Strict-by-source-datatype (matching the reference engine): the expression must already
                // resolve to the right FHIR datatype OBJECT (`Coding`/`Quantity`/`Reference`/`Attachment`).
                // The answer choice normalizer maps the object's class to the correct `value[x]` key, so the
                // object is passed through intact. A bare scalar for a complex item is a mismatch (the engine
                // rejects it), never silently coerced. Binding-driven `code`→`Coding` promotion (a bare code
                // systematised via the item's value-set binding) is deferred — see the sdc-populate backlog.
                return \is_object($value) ? $value : $this->mismatch($itemType, $linkId, $factory, $issues);
            default:
                return $this->unsupportedTypeIssue($itemType, $linkId, $factory, $issues);
        }
    }

    /**
     * Record that an item type has no answer coercion yet (or the item declares no type) as a warning, and
     * return null (no answer). Shared by the null-type guard and the switch default so both report the same
     * "not yet supported" message.
     *
     * @param list<object> $issues
     */
    private function unsupportedTypeIssue(?string $itemType, string $linkId, PopulateModelFactory $factory, array &$issues): null
    {
        $issues[] = $factory->issue(
            IssueSeverity::warning->value,
            IssueType::processingfailure->value,
            \sprintf(
                "initialExpression for item '%s' produced a value for item type '%s', whose answer "
                . 'coercion is not yet supported; the answer was skipped.',
                $linkId,
                $itemType ?? '(none)',
            ),
        );

        return null;
    }

    /**
     * Wrap a scalar into a temporal primitive (`date`/`dateTime`/`time`), reporting a mismatch when the
     * scalar cannot be parsed as that FHIR temporal type.
     *
     * @param list<object> $issues
     */
    private function coerceTemporal(string $itemType, string $scalar, string $linkId, PopulateModelFactory $factory, array &$issues): mixed
    {
        try {
            return match ($itemType) {
                'date'     => $factory->dateValue($scalar),
                'dateTime' => $factory->dateTimeValue($scalar),
                default    => $factory->timeValue($scalar),
            };
        } catch (\Throwable) {
            return $this->mismatch($itemType, $linkId, $factory, $issues, \sprintf('value "%s" is not a valid %s', $scalar, $itemType));
        }
    }

    /**
     * Record a coercion mismatch as a warning and return null (no answer). Centralises the
     * "observable, not silent" discipline for every failed coercion branch.
     *
     * @param list<object> $issues
     */
    private function mismatch(?string $itemType, string $linkId, PopulateModelFactory $factory, array &$issues, ?string $detail = null): null
    {
        $issues[] = $factory->issue(
            IssueSeverity::warning->value,
            IssueType::processingfailure->value,
            \sprintf(
                "initialExpression for item '%s' produced a value incompatible with item type '%s'%s; the answer was skipped.",
                $linkId,
                $itemType ?? '(none)',
                $detail !== null ? ' (' . $detail . ')' : '',
            ),
        );

        return null;
    }
}
