<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment as R4Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding as R4Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity as R4Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference as R4Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResourceType as R4ResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem as R4Item;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemAnswerOption as R4AnswerOption;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen as R4EnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource as R4Questionnaire;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4ResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4Answer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource as R4Response;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment as R4BAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding as R4BCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity as R4BQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference as R4BReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ResourceType as R4BResourceType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItem as R4BItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItemAnswerOption as R4BAnswerOption;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItemEnableWhen as R4BEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource as R4BQuestionnaire;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4BResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4BAnswer;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponseResource as R4BResponse;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment as R5Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding as R5Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity as R5Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference as R5Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Enum\ResourceType as R5ResourceType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem as R5Item;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemAnswerOption as R5AnswerOption;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemEnableWhen as R5EnableWhen;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5Questionnaire;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R5ResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R5Answer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource as R5Response;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Ucum\UcumConverter;
use Brick\DateTime\LocalDate;
use Brick\DateTime\TimeZone;
use Brick\DateTime\YearMonth;

/**
 * Validates a QuestionnaireResponse against its source Questionnaire (R4, R4B, and R5).
 *
 * Implements the core structural rules from the FHIR specification:
 *  1. linkId match — every response item linkId must exist in the source Questionnaire (error)
 *  2. placement — a response item must sit at the position its linkId is declared at in the
 *     Questionnaire hierarchy (error)
 *  3. required items — required, enabled items must be answered when the response status is
 *     completed or amended, checked per parent instance: an absent optional parent exempts its
 *     children, and a required group needs at least one answered descendant question (error)
 *  4. repeats — non-repeating items must occur at most once per parent and carry at most one
 *     answer (error)
 *  5. answer type — the answer value type must match the declared item type (warning)
 *  6. enableWhen — items present while their enableWhen conditions are unsatisfied (warning)
 *  7. answer constraints — item constraint extensions compared against the answer value (error):
 *     min/max value, min/max length, min/max occurs, decimal places, regex (M13). ADR-007
 *     amendment (2026-06-04).
 *  8. value-domain rules (error, M14): non-answerable item types (display/abstract question with an
 *     answer), answerOption membership incl. the exclusive option, Reference target resource-type /
 *     URL well-formedness, Attachment content-type / size / data-size consistency, and url format.
 *
 * Type dispatch uses raw code strings, never the generated QuestionnaireItemType enum — the
 * enum only contains the three hierarchy-root codes (see footgun generated-enum-hierarchy-gap).
 *
 * enableWhen answer lookup is response-global: a documented approximation of the spec's
 * nearest-occurrence resolution, exact whenever the referenced question occurs once (see
 * ADR-007 addendum "enableWhen answer-lookup scoping").
 *
 * @phpstan-type AnyQuestionnaire R4Questionnaire|R4BQuestionnaire|R5Questionnaire
 * @phpstan-type AnyResponse R4Response|R4BResponse|R5Response
 * @phpstan-type AnyItem R4Item|R4BItem|R5Item
 * @phpstan-type AnyResponseItem R4ResponseItem|R4BResponseItem|R5ResponseItem
 * @phpstan-type AnyAnswer R4Answer|R4BAnswer|R5Answer
 * @phpstan-type AnyReference R4Reference|R4BReference|R5Reference
 * @phpstan-type AnyAttachment R4Attachment|R4BAttachment|R5Attachment
 * @phpstan-type AnyEnableWhen R4EnableWhen|R4BEnableWhen|R5EnableWhen
 * @phpstan-type ResponseIndex array<string, list<array{item: R4ResponseItem|R4BResponseItem|R5ResponseItem, path: string}>>
 * @phpstan-type ItemIndex array<string, R4Item|R4BItem|R5Item>
 * @phpstan-type ParentIndex array<string, string|null>
 * @phpstan-type UnitOption array{system: string|null, code: string|null}
 * @phpstan-type ItemConstraints array{minValue?: mixed, maxValue?: mixed, minLength?: int, maxLength?: int, maxDecimalPlaces?: int, regex?: string, minOccurs?: int, maxOccurs?: int, minQuantity?: mixed, maxQuantity?: mixed, unitOption?: list<array{system: string|null, code: string|null}>}
 */
final class FHIRQuestionnaireValidator implements FHIRQuestionnaireValidatorInterface
{
    /**
     * Expected answer value types per declared item type, as class basenames or scalar type
     * names. FHIR decimal deserializes to scalar PHP string; FHIR string to StringPrimitive —
     * the two are distinguishable (M03 spike, AbstractFHIRNormalizer::resolveChoiceVariant).
     * 'coding' is the R5 replacement for choice/open-choice.
     */
    private const array TYPE_MAP = [
        'boolean'     => ['bool'],
        'decimal'     => ['string'],
        'integer'     => ['int'],
        'date'        => ['DatePrimitive'],
        'dateTime'    => ['DateTimePrimitive'],
        'time'        => ['TimePrimitive'],
        'string'      => ['StringPrimitive'],
        'text'        => ['StringPrimitive'],
        'url'         => ['UriPrimitive'],
        'choice'      => ['Coding'],
        'open-choice' => ['Coding', 'StringPrimitive'],
        'coding'      => ['Coding'],
        'attachment'  => ['Attachment'],
        'reference'   => ['Reference'],
        'quantity'    => ['Quantity'],
    ];

    private const array ANSWERABLE_STATUSES = ['completed', 'amended'];

    /**
     * FHIR item-constraint extension URLs. These canonical URLs are shared across R4/R4B/R5 and
     * are read off the source Questionnaire item, then compared against the response answer — no
     * terminology or server context (ADR-007 amendment 2026-06-04). `maxLength` is a core item
     * property, not an extension, so it has no URL here.
     */
    private const string EXT_MIN_VALUE          = 'http://hl7.org/fhir/StructureDefinition/minValue';

    private const string EXT_MAX_VALUE          = 'http://hl7.org/fhir/StructureDefinition/maxValue';

    private const string EXT_MIN_LENGTH         = 'http://hl7.org/fhir/StructureDefinition/minLength';

    private const string EXT_MAX_DECIMAL_PLACES = 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces';

    private const string EXT_REGEX              = 'http://hl7.org/fhir/StructureDefinition/regex';

    private const string EXT_MIN_OCCURS         = 'http://hl7.org/fhir/StructureDefinition/questionnaire-minOccurs';

    private const string EXT_MAX_OCCURS         = 'http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs';

    /**
     * M14 value-domain extension URLs (ADR-007 amendment). `optionExclusive` marks an answerOption
     * that cannot be co-selected; `referenceResource` constrains a Reference answer's target type;
     * `mimeType` and `maxSize` constrain an Attachment answer's content type and size.
     */
    private const string EXT_OPTION_EXCLUSIVE   = 'http://hl7.org/fhir/StructureDefinition/questionnaire-optionExclusive';

    private const string EXT_REFERENCE_RESOURCE = 'http://hl7.org/fhir/StructureDefinition/questionnaire-referenceResource';

    private const string EXT_MIME_TYPE          = 'http://hl7.org/fhir/StructureDefinition/mimeType';

    private const string EXT_MAX_SIZE           = 'http://hl7.org/fhir/StructureDefinition/maxSize';

    /**
     * M15 quantity-answer extension URLs (ADR-007 amendment). Quantity min/max bounds are NOT the
     * generic minValue/maxValue extensions M13 handles — they are the SDC-specific
     * sdc-questionnaire-min/maxQuantity carriers, each holding a valueQuantity. `unitOption` lists
     * the inline UCUM codings an answer's unit may take (a coded enumeration). `unitValueSet` binds
     * the unit to a terminology value set (M02, questionnaire-terminology-validation plan).
     */
    private const string EXT_MIN_QUANTITY       = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-minQuantity';

    private const string EXT_MAX_QUANTITY       = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-maxQuantity';

    private const string EXT_UNIT_OPTION        = 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitOption';

    private const string EXT_UNIT_VALUE_SET     = 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet';

    /** Canonical URL for the preferred terminology server extension (R4/R4B/R5 shared). */
    private const string EXT_PREFERRED_TERMINOLOGY_SERVER = 'http://hl7.org/fhir/StructureDefinition/preferredTerminologyServer';

    /** Kanta PHR enableWhenExpression variant — carries a FHIRPath expression as `valueString`. */
    private const string EXT_ENABLE_WHEN_EXPRESSION_KANTA = 'http://phr.kanta.fi/StructureDefinition/fiphr-ext-questionnaire-enablewhen';

    /** Canonical SDC enableWhenExpression — carries the expression as `valueExpression.expression`. */
    private const string EXT_ENABLE_WHEN_EXPRESSION_SDC = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-enableWhenExpression';

    /**
     * Item types that carry no answer value of their own: `display` is presentational and the
     * abstract `question` root is never a concrete answerable type. A `group` is intentionally
     * excluded — its structure is checked by the required-children rules, not here. An answer on
     * one of these is a structural error (FHIR reports it fatal; we surface it as an error).
     */
    private const array NON_ANSWERABLE_TYPES    = ['display', 'question'];

    private ?object $currentResponse = null;

    /** @var ItemIndex */
    private array $currentItemIndex = [];

    /** @var ParentIndex */
    private array $currentParentOf = [];

    /** @var AnyQuestionnaire|null */
    private R4Questionnaire|R4BQuestionnaire|R5Questionnaire|null $currentQuestionnaire = null;

    public function __construct(
        private readonly UcumConverter $ucum = new UcumConverter(),
        private readonly FHIRPathService $pathService = new FHIRPathService(),
        private readonly ?FHIRTerminologyClientInterface $terminologyClient = null,
        private readonly ?FHIRTerminologyClientFactoryInterface $clientFactory = null,
    ) {
    }

    public function validate(
        object $questionnaire,
        object $response,
        bool $strictStatus = true,
    ): FHIRValidationReport {
        if (
            !$questionnaire instanceof R4Questionnaire
            && !$questionnaire instanceof R4BQuestionnaire
            && !$questionnaire instanceof R5Questionnaire
        ) {
            throw new \InvalidArgumentException(sprintf('Expected a QuestionnaireResource (R4, R4B, or R5), got %s.', $questionnaire::class));
        }

        if (
            !$response instanceof R4Response
            && !$response instanceof R4BResponse
            && !$response instanceof R5Response
        ) {
            throw new \InvalidArgumentException(sprintf('Expected a QuestionnaireResponseResource (R4, R4B, or R5), got %s.', $response::class));
        }

        $this->currentResponse      = $response;
        $this->currentQuestionnaire = $questionnaire;

        try {
            $itemIndex = [];
            $parentOf  = [];
            $this->indexQuestionnaireItems($questionnaire->item, $itemIndex, $parentOf, null);

            $this->currentItemIndex = $itemIndex;
            $this->currentParentOf  = $parentOf;

            $responseIndex = [];
            $this->indexResponseItems($response->item, 'item', $responseIndex);

            $violations = [];

            $this->checkQuestionnaireStatus($questionnaire, $violations);

            $checkRequired = $strictStatus && \in_array((string) ($response->status ?? ''), self::ANSWERABLE_STATUSES, true);
            $isInProgress  = (string) ($response->status ?? '') === 'in-progress';

            $this->walkResponseItems(
                $response->item,
                'item',
                null,
                true,
                $checkRequired,
                $isInProgress,
                $itemIndex,
                $parentOf,
                $responseIndex,
                $violations,
            );
            $this->checkEnableWhenReferences($itemIndex, $violations);

            if ($checkRequired) {
                // The response root is the single instance for top-level questionnaire items.
                $this->checkRequiredChildren($questionnaire->item, array_values($response->item), 'item', $itemIndex, $responseIndex, $violations);
            }

            return new FHIRValidationReport($violations);
        } finally {
            $this->currentResponse      = null;
            $this->currentQuestionnaire = null;
            $this->currentItemIndex     = [];
            $this->currentParentOf      = [];
        }
    }

    /**
     * Builds a flat linkId → item index and a linkId → parent-linkId index by walking the
     * questionnaire item tree.
     *
     * linkIds are spec-unique within a Questionnaire (invariant que-2), so flat maps suffice.
     * Items with a null linkId are skipped; base validation reports the missing required field.
     * Children of a linkId-less item are attributed to the nearest linkId-bearing ancestor so
     * placement checking degrades gracefully on invalid questionnaires.
     *
     * @param array<R4Item|R4BItem|R5Item>         $items
     * @param array<string, R4Item|R4BItem|R5Item> $index
     * @param ParentIndex                          $parentOf
     */
    private function indexQuestionnaireItems(array $items, array &$index, array &$parentOf, ?string $parentLinkId): void
    {
        foreach ($items as $item) {
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($linkId !== '') {
                $index[$linkId]    = $item;
                $parentOf[$linkId] = $parentLinkId;
            }

            $this->indexQuestionnaireItems($item->item, $index, $parentOf, $linkId !== '' ? $linkId : $parentLinkId);
        }
    }

    /**
     * Builds a flat linkId → response-item-occurrences index, recursing into both nested items
     * and answer-scoped child items. List-valued: a linkId legitimately repeats when the
     * questionnaire item declares repeats=true.
     *
     * @param array<R4ResponseItem|R4BResponseItem|R5ResponseItem> $items
     * @param ResponseIndex                                        $index
     */
    private function indexResponseItems(array $items, string $pathPrefix, array &$index): void
    {
        foreach (array_values($items) as $i => $item) {
            $path = sprintf('%s[%d]', $pathPrefix, $i);

            if ($item->linkId !== null && (string) $item->linkId !== '') {
                $index[(string) $item->linkId][] = ['item' => $item, 'path' => $path];
            }

            $this->indexResponseItems($item->item, $path . '.item', $index);

            foreach (array_values($item->answer) as $j => $answer) {
                $this->indexResponseItems($answer->item, sprintf('%s.answer[%d].item', $path, $j), $index);
            }
        }
    }

    /**
     * Walks the response item tree applying rules 1 (linkId match), 2 (placement),
     * 3 (per-instance required children), 4b (answer cardinality), 5 (answer type), and
     * 6 (item present while disabled).
     *
     * $parentLinkId is the linkId of the enclosing matched item (null at the response root);
     * $parentKnown is false when the enclosing item was unmatched or linkId-less, which
     * suspends placement checking for this level. $checkRequired carries the status gate and
     * the disabled-subtree exemption: children of a disabled item are never required.
     *
     * @param array<R4ResponseItem|R4BResponseItem|R5ResponseItem> $items
     * @param ItemIndex                                            $itemIndex
     * @param ParentIndex                                          $parentOf
     * @param ResponseIndex                                        $responseIndex
     * @param list<FHIRValidationViolation>                        $violations
     */
    private function walkResponseItems(
        array $items,
        string $pathPrefix,
        ?string $parentLinkId,
        bool $parentKnown,
        bool $checkRequired,
        bool $isInProgress,
        array $itemIndex,
        array $parentOf,
        array $responseIndex,
        array &$violations,
    ): void {
        $this->checkSiblingOccurrences($items, $pathPrefix, $itemIndex, $violations);

        foreach (array_values($items) as $i => $item) {
            $path   = sprintf('%s[%d]', $pathPrefix, $i);
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            // Context for this item's children; unmatched or linkId-less items suspend
            // placement checking below them but pass the required gate through unchanged.
            $childParentLinkId  = $parentLinkId;
            $childParentKnown   = false;
            $childCheckRequired = $checkRequired;

            if ($linkId !== '') {
                $questionnaireItem = $itemIndex[$linkId] ?? null;

                if ($questionnaireItem === null) {
                    $violations[] = $this->violation(
                        'error',
                        $path . '.linkId',
                        sprintf("Response item linkId '%s' does not exist in the source Questionnaire.", $linkId),
                    );
                } else {
                    if ($parentKnown && ($parentOf[$linkId] ?? null) !== $parentLinkId) {
                        $expected     = $parentOf[$linkId] ?? null;
                        $violations[] = $this->violation(
                            'error',
                            $path,
                            $expected === null
                                ? sprintf("Item '%s' is not declared at this position in the Questionnaire; expected at the response root.", $linkId)
                                : sprintf("Item '%s' is not declared at this position in the Questionnaire; expected under item '%s'.", $linkId, $expected),
                        );
                    }

                    $this->checkAnswerCardinality($item, $questionnaireItem, $linkId, $path, $isInProgress, $violations);
                    $this->checkAnswerTypes($item, $questionnaireItem, $linkId, $path, $violations);
                    $this->checkAnswerConstraints($item, $questionnaireItem, $linkId, $path, $violations);
                    $this->checkValueDomainRules($item, $questionnaireItem, $linkId, $path, $violations);

                    $evaluating = [];
                    $enabled    = $this->isItemEnabled($questionnaireItem, $itemIndex, $responseIndex, $evaluating);

                    if (!$enabled) {
                        $violations[] = $this->violation(
                            'warning',
                            $path,
                            sprintf("Item '%s' is present but its enableWhen conditions are not satisfied.", $linkId),
                        );
                    }

                    if ($checkRequired && $enabled && $questionnaireItem->item !== []) {
                        $this->checkRequiredChildren(
                            $questionnaireItem->item,
                            $this->collectChildResponseItems($item),
                            $path,
                            $itemIndex,
                            $responseIndex,
                            $violations,
                        );
                    }

                    $childParentLinkId  = $linkId;
                    $childParentKnown   = true;
                    $childCheckRequired = $checkRequired && $enabled;
                }
            }

            $this->walkResponseItems(
                $item->item,
                $path . '.item',
                $childParentLinkId,
                $childParentKnown,
                $childCheckRequired,
                $isInProgress,
                $itemIndex,
                $parentOf,
                $responseIndex,
                $violations,
            );

            foreach (array_values($item->answer) as $j => $answer) {
                $this->walkResponseItems(
                    $answer->item,
                    sprintf('%s.answer[%d].item', $path, $j),
                    $childParentLinkId,
                    $childParentKnown,
                    $childCheckRequired,
                    $isInProgress,
                    $itemIndex,
                    $parentOf,
                    $responseIndex,
                    $violations,
                );
            }
        }
    }

    /**
     * Rule 4a — a non-repeating item must not occur more than once among its siblings.
     *
     * Occurrence counting is sibling-scoped, not response-global: a non-repeating child of a
     * repeating group legitimately appears once per group instance, but never twice within
     * the same parent.
     *
     * @param array<R4ResponseItem|R4BResponseItem|R5ResponseItem> $items
     * @param ItemIndex                                            $itemIndex
     * @param list<FHIRValidationViolation>                        $violations
     */
    private function checkSiblingOccurrences(array $items, string $pathPrefix, array $itemIndex, array &$violations): void
    {
        $positions = [];

        foreach (array_values($items) as $i => $item) {
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($linkId !== '') {
                $positions[$linkId][] = $i;
            }
        }

        foreach ($positions as $linkId => $indexes) {
            if (\count($indexes) < 2) {
                continue;
            }

            $questionnaireItem = $itemIndex[$linkId] ?? null;

            if ($questionnaireItem !== null && $questionnaireItem->repeats !== true) {
                $violations[] = $this->violation(
                    'error',
                    sprintf('%s[%d]', $pathPrefix, $indexes[1]),
                    sprintf(
                        "Item '%s' does not repeat but appears %d times in the response.",
                        $linkId,
                        \count($indexes),
                    ),
                );
            }
        }
    }

    private function violation(string $severity, string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: $severity,
            path: $path,
            message: $message,
            constraintClass: FHIRQuestionnaireConstraint::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Emit warnings when the Questionnaire is not in an active published state.
     *
     * Checks: (1) status is draft or retired; (2) effectivePeriod.end is in the past;
     * (3) effectivePeriod.start is in the future.
     *
     * @param R4Questionnaire|R4BQuestionnaire|R5Questionnaire $questionnaire
     * @param list<FHIRValidationViolation>                    $violations
     */
    private function checkQuestionnaireStatus(
        R4Questionnaire|R4BQuestionnaire|R5Questionnaire $questionnaire,
        array &$violations,
    ): void {
        $id     = $questionnaire->id ?? 'unknown';
        $status = (string) ($questionnaire->status ?? '');

        if ($status === 'draft' || $status === 'retired') {
            $violations[] = $this->violation(
                'warning',
                'Questionnaire.status',
                "Questionnaire '{$id}' has status '{$status}' — responses may not reflect a published form.",
            );
        }

        $period = $questionnaire->effectivePeriod ?? null;

        if ($period === null) {
            return;
        }

        $today = LocalDate::now(TimeZone::utc())->__toString();

        $endDt = ($period->end ?? null)?->value;

        if ($endDt !== null) {
            $end = $this->normalizeFhirDateEnd((string) $endDt);

            if ($end < $today) {
                $violations[] = $this->violation(
                    'warning',
                    'Questionnaire.effectivePeriod.end',
                    "Questionnaire '{$id}' effective period ended on {$end} — responses may be invalid.",
                );
            }
        }

        $startDt = ($period->start ?? null)?->value;

        if ($startDt !== null) {
            $start = $this->normalizeFhirDateStart((string) $startDt);

            if ($start > $today) {
                $violations[] = $this->violation(
                    'warning',
                    'Questionnaire.effectivePeriod.start',
                    "Questionnaire '{$id}' effective period does not start until {$start} — responses may be premature.",
                );
            }
        }
    }

    /**
     * Pad a FHIR partial date to its latest possible Y-m-d value for end-of-period comparisons.
     * "2021" → "2021-12-31"; "2021-12" → last day of that month; full dates are truncated to 10 chars.
     */
    private function normalizeFhirDateEnd(string $date): string
    {
        if (preg_match('/^\d{4}$/', $date) === 1) {
            return $date . '-12-31';
        }

        if (preg_match('/^\d{4}-\d{2}$/', $date) === 1) {
            $month = (int) substr($date, 5, 2);
            if ($month < 1 || $month > 12) {
                return substr($date, 0, 10);
            }

            return $date . '-' . YearMonth::parse($date)->getLengthOfMonth();
        }

        return substr($date, 0, 10);
    }

    /**
     * Pad a FHIR partial date to its earliest possible Y-m-d value for start-of-period comparisons.
     * "2022" → "2022-01-01"; "2022-06" → "2022-06-01"; full dates are truncated to 10 chars.
     */
    private function normalizeFhirDateStart(string $date): string
    {
        if (preg_match('/^\d{4}$/', $date) === 1) {
            return $date . '-01-01';
        }

        if (preg_match('/^\d{4}-\d{2}$/', $date) === 1) {
            return $date . '-01';
        }

        return substr($date, 0, 10);
    }

    /**
     * Rule 4b — a non-repeating item must carry at most one answer.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkAnswerCardinality(
        object $item,
        object $questionnaireItem,
        string $linkId,
        string $path,
        bool $isInProgress,
        array &$violations,
    ): void {
        $answerCount = \count($item->answer);

        if ($questionnaireItem->repeats !== true && $answerCount > 1) {
            $violations[] = $this->violation(
                $isInProgress ? 'warning' : 'error',
                $path . '.answer',
                sprintf(
                    "Item '%s' does not allow multiple answers (repeats is not true) but %d answers were provided.",
                    $linkId,
                    $answerCount,
                ),
            );
        }
    }

    /**
     * Rule 5 — the answer value type must match the declared item type (warning severity:
     * a mismatch may be legitimate in some contexts per the specification).
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkAnswerTypes(
        object $item,
        object $questionnaireItem,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        $type = $questionnaireItem->type !== null ? (string) $questionnaireItem->type : '';

        $expected = self::TYPE_MAP[$type] ?? null;
        if ($expected === null) {
            return; // group, display, or unknown type code — no answer value expectation
        }

        foreach (array_values($item->answer) as $j => $answer) {
            if ($answer->value === null) {
                continue;
            }

            $actual = \is_object($answer->value)
                ? $this->classBasename($answer->value::class)
                : get_debug_type($answer->value);

            if (!\in_array($actual, $expected, true)) {
                $violations[] = $this->violation(
                    'warning',
                    sprintf('%s.answer[%d].value', $path, $j),
                    sprintf(
                        "Answer type '%s' does not match declared item type '%s' for item '%s'.",
                        $actual,
                        $type,
                        $linkId,
                    ),
                );
            }

            if ($type === 'string'
                && \is_object($answer->value)
                && $answer->value instanceof \Stringable
                && preg_match('/[\r\n]/', (string) $answer->value) === 1
            ) {
                $violations[] = $this->violation(
                    'error',
                    sprintf('%s.answer[%d].value', $path, $j),
                    sprintf(
                        "Answer of type 'string' for item '%s' must not contain line breaks (\\r or \\n) — use type 'text'.",
                        $linkId,
                    ),
                );
            }
        }
    }

    private function classBasename(string $fqcn): string
    {
        $position = strrpos($fqcn, '\\');

        return $position === false ? $fqcn : substr($fqcn, $position + 1);
    }

    /**
     * Rule 7 — item constraint extensions (ADR-007 amendment, milestones M13–M16). Reads the
     * item's normalized constraints once, then applies them: the answer-count bounds (min/max
     * occurs) once per item, and the per-value bounds (min/max value, length, decimal places,
     * regex) for each answer.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkAnswerConstraints(
        object $item,
        object $questionnaireItem,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        $constraints = $this->itemConstraints($questionnaireItem);

        if ($constraints === []) {
            return;
        }

        $this->checkOccurs($item, $constraints, $linkId, $path, $violations);

        foreach (array_values($item->answer) as $j => $answer) {
            if ($answer->value === null) {
                continue;
            }

            $valuePath = sprintf('%s.answer[%d].value', $path, $j);
            $this->checkValueBounds($answer->value, $constraints, $linkId, $valuePath, $violations);
            $this->checkLength($answer->value, $constraints, $linkId, $valuePath, $violations);
            $this->checkDecimalPlaces($answer->value, $constraints, $linkId, $valuePath, $violations);
            $this->checkRegex($answer->value, $constraints, $linkId, $valuePath, $violations);
            $this->checkQuantityConstraints($answer->value, $constraints, $linkId, $valuePath, $violations);
        }
    }

    /**
     * Reads an item's typed constraint carriers into a normalized struct: the core `maxLength`
     * property plus the minValue/maxValue/minLength/maxDecimalPlaces/regex/min-maxOccurs
     * extensions (their canonical URLs are shared across R4/R4B/R5). Absent carriers are omitted,
     * so an empty result means "no constraints to check".
     *
     * @param R4Item|R4BItem|R5Item $questionnaireItem
     *
     * @return ItemConstraints
     */
    private function itemConstraints(object $questionnaireItem): array
    {
        $constraints = [];

        if ($questionnaireItem->maxLength !== null) {
            $constraints['maxLength'] = (int) $questionnaireItem->maxLength;
        }

        foreach ($questionnaireItem->extension as $extension) {
            $url   = $extension->url;
            $value = isset($extension->value) ? $extension->value : null;
            if ($url === null || $value === null) {
                continue;
            }

            switch ($url) {
                case self::EXT_MIN_VALUE:
                    $constraints['minValue'] = $value;
                    break;
                case self::EXT_MAX_VALUE:
                    $constraints['maxValue'] = $value;
                    break;
                case self::EXT_MIN_LENGTH:
                    $int = $this->extensionInt($value);
                    if ($int !== null) {
                        $constraints['minLength'] = $int;
                    }
                    break;
                case self::EXT_MAX_DECIMAL_PLACES:
                    $int = $this->extensionInt($value);
                    if ($int !== null) {
                        $constraints['maxDecimalPlaces'] = $int;
                    }
                    break;
                case self::EXT_REGEX:
                    $regex = $this->extensionString($value);
                    if ($regex !== null) {
                        $constraints['regex'] = $regex;
                    }
                    break;
                case self::EXT_MIN_OCCURS:
                    $int = $this->extensionInt($value);
                    if ($int !== null) {
                        $constraints['minOccurs'] = $int;
                    }
                    break;
                case self::EXT_MAX_OCCURS:
                    $int = $this->extensionInt($value);
                    if ($int !== null) {
                        $constraints['maxOccurs'] = $int;
                    }
                    break;
                case self::EXT_MIN_QUANTITY:
                    $constraints['minQuantity'] = $value;
                    break;
                case self::EXT_MAX_QUANTITY:
                    $constraints['maxQuantity'] = $value;
                    break;
                case self::EXT_UNIT_OPTION:
                    $coding = $this->codingParts($value);
                    if ($coding !== null) {
                        $options                   = $constraints['unitOption'] ?? [];
                        $options[]                 = $coding;
                        $constraints['unitOption'] = $options;
                    }
                    break;
            }
        }

        return $constraints;
    }

    /**
     * Coerces an integer-valued extension value (PHP int, numeric primitive, or numeric string)
     * to int; returns null when the value is not integer-like.
     */
    private function extensionInt(mixed $value): ?int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_string($value)) {
            return is_numeric($value) ? (int) $value : null;
        }

        if ($value instanceof \Stringable) {
            $string = (string) $value;

            return is_numeric($string) ? (int) $string : null;
        }

        return null;
    }

    /**
     * Extracts a string from a string-valued extension value (raw string or string primitive);
     * returns null for non-string values.
     */
    private function extensionString(mixed $value): ?string
    {
        if (\is_string($value)) {
            return $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return null;
    }

    /**
     * min/max occurs: the answer count of a single response item must lie within the item's
     * questionnaire-min/maxOccurs bounds. Count-based (not per-answer); minOccurs is only enforced
     * for an item that is present in the response.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param ItemConstraints                               $constraints
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkOccurs(object $item, array $constraints, string $linkId, string $path, array &$violations): void
    {
        if (!\array_key_exists('minOccurs', $constraints) && !\array_key_exists('maxOccurs', $constraints)) {
            return;
        }

        $count = \count($item->answer);

        if (\array_key_exists('minOccurs', $constraints) && $count < $constraints['minOccurs']) {
            $violations[] = $this->violation(
                'error',
                $path . '.answer',
                sprintf("Item '%s' requires at least %d answer(s) but %d were provided.", $linkId, $constraints['minOccurs'], $count),
            );
        }

        if (\array_key_exists('maxOccurs', $constraints) && $count > $constraints['maxOccurs']) {
            $violations[] = $this->violation(
                'error',
                $path . '.answer',
                sprintf("Item '%s' allows at most %d answer(s) but %d were provided.", $linkId, $constraints['maxOccurs'], $count),
            );
        }
    }

    /**
     * min/max value: emit an error when an ordered answer (date, dateTime, decimal, integer) falls
     * outside the item's [minValue, maxValue] bound. Date/dateTime values use period-extension
     * semantics (both answer and bound normalized to the same endpoint so mixed-precision
     * comparisons resolve symmetrically); numeric values compare via comparableValue().
     *
     * @param ItemConstraints               $constraints
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkValueBounds(mixed $answerValue, array $constraints, string $linkId, string $path, array &$violations): void
    {
        // Date/dateTime: a partial bound covers its full period (FHIR R4 §2.24.0.2). Both answer
        // and bound are extended to the same period endpoint so a year-month answer against a
        // year-month bound compares correctly regardless of which side has lower precision.
        $answerDateStr = $this->dateValueString($answerValue);
        if ($answerDateStr !== null) {
            if (array_key_exists('minValue', $constraints)) {
                $boundStr = $this->dateValueString($constraints['minValue']);
                if ($boundStr !== null && $this->normalizeFhirDateStart($answerDateStr) < $this->normalizeFhirDateStart($boundStr)) {
                    $violations[] = $this->violation(
                        'error',
                        $path,
                        sprintf(
                            "Answer '%s' for item '%s' is less than the allowed minimum of '%s'.",
                            $this->displayValue($answerValue),
                            $linkId,
                            $this->displayValue($constraints['minValue']),
                        ),
                    );
                }
            }

            if (array_key_exists('maxValue', $constraints)) {
                $boundStr = $this->dateValueString($constraints['maxValue']);
                if ($boundStr !== null && $this->normalizeFhirDateEnd($answerDateStr) > $this->normalizeFhirDateEnd($boundStr)) {
                    $violations[] = $this->violation(
                        'error',
                        $path,
                        sprintf(
                            "Answer '%s' for item '%s' is greater than the allowed maximum of '%s'.",
                            $this->displayValue($answerValue),
                            $linkId,
                            $this->displayValue($constraints['maxValue']),
                        ),
                    );
                }
            }

            return;
        }

        $actual = $this->comparableValue($answerValue);
        if ($actual === null) {
            return;
        }

        if (array_key_exists('minValue', $constraints)) {
            $min = $this->comparableValue($constraints['minValue']);
            $cmp = $min !== null ? $this->compareScalar($actual, $min) : null;
            if ($cmp !== null && $cmp < 0) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf(
                        "Answer '%s' for item '%s' is less than the allowed minimum of '%s'.",
                        $this->displayValue($answerValue),
                        $linkId,
                        $this->displayValue($constraints['minValue']),
                    ),
                );
            }
        }

        if (array_key_exists('maxValue', $constraints)) {
            $max = $this->comparableValue($constraints['maxValue']);
            $cmp = $max !== null ? $this->compareScalar($actual, $max) : null;
            if ($cmp !== null && $cmp > 0) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf(
                        "Answer '%s' for item '%s' is greater than the allowed maximum of '%s'.",
                        $this->displayValue($answerValue),
                        $linkId,
                        $this->displayValue($constraints['maxValue']),
                    ),
                );
            }
        }
    }

    /**
     * Quantity answer rules (M15): min/max bounds carried by the SDC min/maxQuantity extensions and
     * unit membership carried by unitOption. Unit-aware throughout — comparisons go through the
     * shared UcumConverter so commensurable units (e.g. m vs km) are converted before comparing,
     * and a dimensional mismatch (e.g. kg vs miles) is reported rather than silently compared by
     * bare magnitude. A non-Quantity answer is ignored here (the answer-type rule warns on it).
     *
     * @param ItemConstraints               $constraints
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkQuantityConstraints(mixed $answerValue, array $constraints, string $linkId, string $path, array &$violations): void
    {
        $hasMin        = \array_key_exists('minQuantity', $constraints);
        $hasMax        = \array_key_exists('maxQuantity', $constraints);
        $hasUnitOption = \array_key_exists('unitOption', $constraints);

        if (!$hasMin && !$hasMax && !$hasUnitOption) {
            return;
        }

        $answer = $this->quantityParts($answerValue);
        if ($answer === null) {
            return;
        }

        if ($hasUnitOption) {
            $this->checkUnitOption($answer, $constraints['unitOption'], $linkId, $path, $violations);
        }

        // Bound order mirrors the reference validator: minimum first, then maximum.
        if ($hasMin) {
            $this->checkQuantityBound($answer, $this->quantityParts($constraints['minQuantity']), 'minimum', $linkId, $path, $violations);
        }
        if ($hasMax) {
            $this->checkQuantityBound($answer, $this->quantityParts($constraints['maxQuantity']), 'maximum', $linkId, $path, $violations);
        }
    }

    /**
     * Compares an answer quantity against a single bound. Two preconditions gate the numeric
     * comparison, each an error in its own right (matching the reference validator's per-bound
     * error): both quantities must carry formal UCUM units, and the units must be commensurable.
     * Only when both hold is the converted magnitude compared against the bound.
     *
     * @param array{value: float, unit: string|null, system: string|null, code: string|null}      $answer
     * @param array{value: float, unit: string|null, system: string|null, code: string|null}|null $bound
     * @param list<FHIRValidationViolation>                                                       $violations
     */
    private function checkQuantityBound(array $answer, ?array $bound, string $kind, string $linkId, string $path, array &$violations): void
    {
        if ($bound === null) {
            return;
        }

        if (!$this->hasFormalUnits($bound)) {
            // Bound has no UCUM system — fall back to numeric magnitude comparison so that
            // a Q author who omits system/code still gets range enforcement without unit conversion.
            $cmp = $answer['value'] <=> $bound['value'];
            if ($kind === 'minimum' && $cmp < 0) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf(
                        "Answer '%s' for item '%s' is less than the allowed minimum of '%s'.",
                        $this->quantityDisplay($answer),
                        $linkId,
                        $this->quantityDisplay($bound),
                    ),
                );
            } elseif ($kind === 'maximum' && $cmp > 0) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf(
                        "Answer '%s' for item '%s' is greater than the allowed maximum of '%s'.",
                        $this->quantityDisplay($answer),
                        $linkId,
                        $this->quantityDisplay($bound),
                    ),
                );
            }

            return;
        }

        if (!$this->hasFormalUnits($answer)) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf(
                    "Answer '%s' for item '%s' cannot be compared to the allowed %s of '%s' because no formal units are specified.",
                    $this->quantityDisplay($answer),
                    $linkId,
                    $kind,
                    $this->quantityDisplay($bound),
                ),
            );

            return;
        }

        $cmp = $this->ucum->compare($answer['value'], (string) $answer['code'], $bound['value'], (string) $bound['code']);
        if ($cmp === null) {
            // compare() returns null for two distinct reasons; report them differently rather than
            // asserting a dimensional mismatch the converter cannot actually establish. An
            // unrecognised code is a limitation of this minimal converter, not proof the units are
            // incompatible — only when both codes are known and still don't convert is the failure a
            // genuine cross-dimension mismatch.
            $answerKnown = $this->ucum->knows((string) $answer['code']);
            $boundKnown  = $this->ucum->knows((string) $bound['code']);

            if (!$answerKnown || !$boundKnown) {
                $unsupported = $answerKnown ? $bound['code'] : $answer['code'];
                $message     = sprintf(
                    "Answer '%s' for item '%s' cannot be compared to the allowed %s of '%s' because the unit '%s' is not supported for unit conversion.",
                    $this->quantityDisplay($answer),
                    $linkId,
                    $kind,
                    $this->quantityDisplay($bound),
                    $unsupported,
                );
            } else {
                $message = sprintf(
                    "Answer '%s' for item '%s' cannot be compared to the allowed %s of '%s' because the units are not compatible.",
                    $this->quantityDisplay($answer),
                    $linkId,
                    $kind,
                    $this->quantityDisplay($bound),
                );
            }

            $violations[] = $this->violation('error', $path, $message);

            return;
        }

        if ($kind === 'minimum' && $cmp < 0) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf(
                    "Answer '%s' for item '%s' is less than the allowed minimum of '%s'.",
                    $this->quantityDisplay($answer),
                    $linkId,
                    $this->quantityDisplay($bound),
                ),
            );
        } elseif ($kind === 'maximum' && $cmp > 0) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf(
                    "Answer '%s' for item '%s' is greater than the allowed maximum of '%s'.",
                    $this->quantityDisplay($answer),
                    $linkId,
                    $this->quantityDisplay($bound),
                ),
            );
        }
    }

    /**
     * unitOption membership: a coded answer unit must match one of the allowed codings (by UCUM
     * code, with the system honoured when both sides carry one). An answer without a coded unit is
     * not checked here — there is nothing to match against the coded option list.
     *
     * @param array{value: float, unit: string|null, system: string|null, code: string|null} $answer
     * @param list<array{system: string|null, code: string|null}>                            $allowed
     * @param list<FHIRValidationViolation>                                                  $violations
     */
    private function checkUnitOption(array $answer, array $allowed, string $linkId, string $path, array &$violations): void
    {
        $code = $answer['code'];
        if ($code === null || $code === '') {
            return;
        }

        foreach ($allowed as $option) {
            if (
                $option['code'] === $code
                && ($option['system'] === null || $answer['system'] === null || $option['system'] === $answer['system'])
            ) {
                return;
            }
        }

        $allowedCodes = implode(', ', array_filter(array_map(static fn (array $o): ?string => $o['code'], $allowed)));

        $violations[] = $this->violation(
            'error',
            $path,
            sprintf(
                "Answer unit '%s' for item '%s' is not one of the allowed units (%s).",
                $code,
                $linkId,
                $allowedCodes,
            ),
        );
    }

    /**
     * Extracts the comparison-relevant parts of a Quantity-typed value (R4/R4B/R5). Returns null
     * when the value is not a Quantity or carries no numeric magnitude — either way there is no
     * quantity to compare.
     *
     * @return array{value: float, unit: string|null, system: string|null, code: string|null}|null
     */
    private function quantityParts(mixed $value): ?array
    {
        if (!$value instanceof R4Quantity && !$value instanceof R4BQuantity && !$value instanceof R5Quantity) {
            return null;
        }

        // Deserialization leaves absent optional properties uninitialized (not null), so each is
        // read through isset() — a bare property access on an unset typed property would throw.
        $magnitude = isset($value->value) ? $value->value : null;
        if ($magnitude === null) {
            return null;
        }

        return [
            'value'  => (float) $magnitude,
            'unit'   => isset($value->unit) ? $this->extensionString($value->unit) : null,
            'system' => isset($value->system) ? $this->extensionString($value->system) : null,
            'code'   => isset($value->code) ? $this->extensionString($value->code) : null,
        ];
    }

    /**
     * Extracts a Coding's system/code (R4/R4B/R5) for unitOption comparison; null when the value is
     * not a Coding.
     *
     * @return array{system: string|null, code: string|null}|null
     */
    private function codingParts(mixed $value): ?array
    {
        if (!$value instanceof R4Coding && !$value instanceof R4BCoding && !$value instanceof R5Coding) {
            return null;
        }

        return [
            'system' => isset($value->system) ? $this->extensionString($value->system) : null,
            'code'   => isset($value->code) ? $this->extensionString($value->code) : null,
        ];
    }

    /**
     * A quantity has formal units when it carries a UCUM-coded unit (a code in the UCUM system).
     * Without one, magnitudes cannot be safely compared and the reference validator reports the
     * pair as incomparable.
     *
     * @param array{value: float, unit: string|null, system: string|null, code: string|null} $quantity
     */
    private function hasFormalUnits(array $quantity): bool
    {
        return $quantity['code'] !== null
            && $quantity['code'] !== ''
            && $quantity['system'] === UcumConverter::SYSTEM_URL;
    }

    /**
     * Renders a quantity as "<value> <unit>" for violation messages, preferring the human unit
     * label and falling back to the UCUM code.
     *
     * @param array{value: float, unit: string|null, system: string|null, code: string|null} $quantity
     */
    private function quantityDisplay(array $quantity): string
    {
        $magnitude = $quantity['value'] == (int) $quantity['value']
            ? (string) (int) $quantity['value']
            : (string) $quantity['value'];

        $unit = $quantity['unit'] ?? $quantity['code'];

        return $unit === null || $unit === '' ? $magnitude : $magnitude . ' ' . $unit;
    }

    /**
     * Extracts the date string from a DatePrimitive or DateTimePrimitive value (strips the
     * time component from dateTime values). Returns null for any other type.
     */
    private function dateValueString(mixed $value): ?string
    {
        if (!$value instanceof \Stringable) {
            return null;
        }

        $basename = $this->classBasename($value::class);
        if ($basename !== 'DatePrimitive' && $basename !== 'DateTimePrimitive') {
            return null;
        }

        $str  = (string) $value;
        $tPos = strpos($str, 'T');

        return $str === '' ? null : ($tPos !== false ? substr($str, 0, $tPos) : $str);
    }

    /**
     * Maps an answer value to an orderable scalar for the relational operators: floats for
     * numerics and Quantity magnitudes, strings for date/time/string primitives (ISO formats
     * order lexicographically). Booleans, Codings, and References have no ordering.
     */
    private function comparableValue(mixed $value): float|string|null
    {
        if (\is_bool($value)) {
            return null;
        }

        if (\is_int($value)) {
            return (float) $value;
        }

        if (\is_string($value)) {
            return is_numeric($value) ? (float) $value : null;
        }

        if ($value instanceof R4Quantity || $value instanceof R4BQuantity || $value instanceof R5Quantity) {
            return $value->value !== null ? (float) $value->value : null;
        }

        if (
            $value instanceof R4Coding || $value instanceof R4BCoding || $value instanceof R5Coding
                                       || $value instanceof R4Reference || $value instanceof R4BReference || $value instanceof R5Reference
        ) {
            return null;
        }

        if ($value instanceof \Stringable) {
            $string = (string) $value;

            return $string === '' ? null : $string;
        }

        return null;
    }

    /**
     * Compares two orderable scalars of the same kind, mirroring compareValues()'s relational
     * branch: floats numerically, strings lexicographically (ISO date/time formats order
     * correctly). Returns null when the kinds differ — an incomparable pair is never a violation.
     */
    private function compareScalar(float|string $a, float|string $b): ?int
    {
        if (\is_float($a) && \is_float($b)) {
            return $a <=> $b;
        }

        if (\is_string($a) && \is_string($b)) {
            return $a <=> $b;
        }

        return null;
    }

    /**
     * Renders a value for a violation message without assuming it stringifies (the answer/bound
     * unions include non-Stringable complex types).
     */
    private function displayValue(mixed $value): string
    {
        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (\is_scalar($value) || $value instanceof \Stringable) {
            return (string) $value;
        }

        // Complex answer values (Coding/Reference/Quantity) are not Stringable; fall back to the
        // normalized identity (e.g. "coding:system|code") so the message stays meaningful.
        $normalized = $this->normalizeValue($value);

        return \is_string($normalized) ? $normalized : get_debug_type($value);
    }

    /**
     * Normalizes an answer value to a scalar for equality comparison. Numeric values collapse
     * to float so integer answers equal decimal expectations; complex types collapse to
     * prefixed identity strings (Coding equality is system+code per the specification).
     */
    private function normalizeValue(mixed $value): bool|float|string|null
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\is_int($value)) {
            return (float) $value;
        }

        if (\is_string($value)) {
            return is_numeric($value) ? (float) $value : $value;
        }

        if ($value instanceof R4Coding || $value instanceof R4BCoding || $value instanceof R5Coding) {
            return sprintf('coding:%s|%s', (string) ($value->system ?? ''), (string) ($value->code ?? ''));
        }

        if ($value instanceof R4Quantity || $value instanceof R4BQuantity || $value instanceof R5Quantity) {
            $magnitude = $value->value !== null ? (string) (float) $value->value : '';

            return sprintf('quantity:%s|%s|%s', $magnitude, (string) ($value->system ?? ''), (string) ($value->code ?? ''));
        }

        if ($value instanceof R4Reference || $value instanceof R4BReference || $value instanceof R5Reference) {
            return sprintf('reference:%s', (string) ($value->reference ?? ''));
        }

        if ($value instanceof \Stringable) {
            return (string) $value; // all FHIR primitives stringify to their value
        }

        return null;
    }

    /**
     * min/maxLength: a string-valued answer (string, text, or url) must lie within the item's
     * length bounds (`maxLength` is the core property; `minLength` an extension). Length is counted
     * in Unicode characters. Non-string answers (numerics, dates, complex types) are skipped — the
     * raw PHP `string` answer type carries decimals, not text, so it is intentionally excluded.
     *
     * @param ItemConstraints               $constraints
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkLength(mixed $answerValue, array $constraints, string $linkId, string $path, array &$violations): void
    {
        if (!\array_key_exists('minLength', $constraints) && !\array_key_exists('maxLength', $constraints)) {
            return;
        }

        $string = $this->answerStringValue($answerValue);
        if ($string === null) {
            return;
        }

        $length = mb_strlen($string);

        if (\array_key_exists('minLength', $constraints) && $length < $constraints['minLength']) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Answer for item '%s' is shorter than the minimum length of %d (length %d).", $linkId, $constraints['minLength'], $length),
            );
        }

        if (\array_key_exists('maxLength', $constraints) && $length > $constraints['maxLength']) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Answer for item '%s' is longer than the maximum length of %d (length %d).", $linkId, $constraints['maxLength'], $length),
            );
        }
    }

    /**
     * Extracts text content from a string-family answer (string/text → StringPrimitive,
     * url → UriPrimitive) for length and regex checks; returns null for any other value type so
     * those rules never apply to numerics, dates, or complex types.
     */
    private function answerStringValue(mixed $value): ?string
    {
        if ($value instanceof \Stringable && \in_array($this->classBasename($value::class), ['StringPrimitive', 'UriPrimitive'], true)) {
            return (string) $value;
        }

        return null;
    }

    /**
     * maxDecimalPlaces: a decimal answer must not carry more fractional digits than allowed. FHIR
     * decimals deserialize to a PHP string to preserve their lexical form (incl. trailing zeros),
     * so the place count is taken from that string; non-decimal answers are skipped.
     *
     * @param ItemConstraints               $constraints
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkDecimalPlaces(mixed $answerValue, array $constraints, string $linkId, string $path, array &$violations): void
    {
        if (!\array_key_exists('maxDecimalPlaces', $constraints) || !\is_string($answerValue)) {
            return;
        }

        $dotPosition = strpos($answerValue, '.');
        $places      = $dotPosition === false ? 0 : \strlen(substr($answerValue, $dotPosition + 1));

        if ($places > $constraints['maxDecimalPlaces']) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Answer '%s' for item '%s' has %d decimal places but at most %d are allowed.", $answerValue, $linkId, $places, $constraints['maxDecimalPlaces']),
            );
        }
    }

    /**
     * regex: a string-valued answer must fully match the item's regex constraint. FHIR regexes are
     * implicitly whole-value anchored, so the pattern is wrapped accordingly. An unparseable
     * pattern is skipped rather than raised (it is a Questionnaire authoring error, not a response
     * error).
     *
     * @param ItemConstraints               $constraints
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkRegex(mixed $answerValue, array $constraints, string $linkId, string $path, array &$violations): void
    {
        if (!\array_key_exists('regex', $constraints)) {
            return;
        }

        $string = $this->answerStringValue($answerValue);
        if ($string === null) {
            return;
        }

        $escaped = str_replace('~', '\~', $constraints['regex']);
        $pattern = '~^(?:' . $escaped . ')$~u';
        $matched = @preg_match($pattern, $string);
        if ($matched === false) {
            return; // invalid pattern in the Questionnaire — not a response violation
        }

        if ($matched === 0) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Answer '%s' for item '%s' does not match the required pattern '%s'.", $string, $linkId, $constraints['regex']),
            );
        }
    }

    /**
     * M14 value-domain rules (ADR-007 amendment). Applies the structural and membership rules that
     * read the answer value itself rather than a comparison extension: non-answerable item types,
     * answerOption membership (incl. the exclusive option), Reference target type / URL, Attachment
     * content-type / size, and url primitive format. Each rule is self-guarding, so items without
     * the relevant constraint or value type are untouched.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkValueDomainRules(
        object $item,
        object $questionnaireItem,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        $type = $questionnaireItem->type !== null ? (string) $questionnaireItem->type : '';

        if (\in_array($type, self::NON_ANSWERABLE_TYPES, true) && $this->hasAnswerValue($item)) {
            $violations[] = $this->violation(
                'error',
                $path . '.answer',
                sprintf("Item '%s' of type '%s' cannot have answers.", $linkId, $type),
            );
        }

        $this->checkAnswerOptionMembership($item, $questionnaireItem, $type, $linkId, $path, $violations);
        $this->checkAnswerValueSetMembership($item, $questionnaireItem, $type, $linkId, $path, $violations);

        if ($type === 'quantity') {
            $this->checkUnitValueSetMembership($item, $questionnaireItem, $linkId, $path, $violations);
        }

        foreach (array_values($item->answer) as $j => $answer) {
            $value = $answer->value;
            if ($value === null) {
                continue;
            }

            $valuePath = sprintf('%s.answer[%d].value', $path, $j);

            if ($value instanceof R4Reference || $value instanceof R4BReference || $value instanceof R5Reference) {
                $this->checkReferenceAnswer($value, $questionnaireItem, $linkId, $valuePath, $violations);
            } elseif ($value instanceof R4Attachment || $value instanceof R4BAttachment || $value instanceof R5Attachment) {
                $this->checkAttachmentAnswer($value, $questionnaireItem, $linkId, $valuePath, $violations);
            } elseif ($type === 'url' && $value instanceof \Stringable && $this->classBasename($value::class) === 'UriPrimitive') {
                $this->checkUrlFormat($value, $linkId, $valuePath, $violations);
            }
        }
    }

    /**
     * Whether the response item carries at least one answer with a non-null value. An answer
     * element with no value (e.g. answer: [{}]) answers nothing and does not count — the model
     * declares QuestionnaireResponseItemAnswer::$value nullable, so an empty element is valid
     * FHIR but must not satisfy a required item or a required group's descendant rule.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     */
    private function hasAnswerValue(object $item): bool
    {
        foreach ($item->answer as $answer) {
            if ($answer->value !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * answerValueSet membership (M02): when the item declares an answerValueSet and a terminology
     * client is configured, each answer is validated against it. Coding answers call
     * validateCoding(); StringPrimitive answers (string type) call validateCode(). For open-choice
     * items, a StringPrimitive answer is always valid per the FHIR R4 spec — only Codings are
     * checked. Returns immediately when no client is wired (backward-compatible, zero-config).
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkAnswerValueSetMembership(
        object $item,
        object $questionnaireItem,
        string $type,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        if ($this->terminologyClient === null && $this->clientFactory === null) {
            return;
        }

        $vsUrl = $questionnaireItem->answerValueSet !== null ? (string) $questionnaireItem->answerValueSet : '';
        if ($vsUrl === '') {
            return;
        }

        $client = $this->resolveClientForItem($linkId);

        foreach (array_values($item->answer) as $j => $answer) {
            $value = $answer->value;
            if ($value === null) {
                continue;
            }

            $valuePath = sprintf('%s.answer[%d].value', $path, $j);

            if ($value instanceof R4Coding || $value instanceof R4BCoding || $value instanceof R5Coding) {
                $parts  = $this->codingParts($value);
                $system = (string) ($parts['system'] ?? '');
                $code   = (string) ($parts['code'] ?? '');
                if ($code === '') {
                    $violations[] = $this->violation(
                        'error',
                        $valuePath,
                        sprintf(
                            "Answer coding for item '%s' has no code; a code is required to check value set '%s'.",
                            $linkId,
                            $vsUrl,
                        ),
                    );
                } else {
                    $display = $this->extensionString($value->display ?? null) ?? '';
                    if ($display !== '') {
                        $result = $client->validateCodingWithDisplay($vsUrl, $system, $code, $display);
                        if (!$result->valid) {
                            $violations[] = $this->violation(
                                'error',
                                $valuePath,
                                sprintf(
                                    "Answer coding '%s|%s' for item '%s' is not a member of value set '%s'.",
                                    $system,
                                    $code,
                                    $linkId,
                                    $vsUrl,
                                ),
                            );
                        } elseif ($result->correctDisplay !== null) {
                            $violations[] = $this->violation(
                                'warning',
                                $valuePath,
                                sprintf(
                                    "Coding display '%s' should be '%s' for system %s code %s.",
                                    $display,
                                    $result->correctDisplay,
                                    $system,
                                    $code,
                                ),
                            );
                        }
                    } elseif (!$client->validateCoding($vsUrl, $system, $code)) {
                        $violations[] = $this->violation(
                            'error',
                            $valuePath,
                            sprintf(
                                "Answer coding '%s|%s' for item '%s' is not a member of value set '%s'.",
                                $system,
                                $code,
                                $linkId,
                                $vsUrl,
                            ),
                        );
                    }
                }
            } elseif (
                $type !== 'open-choice'
                && $value instanceof \Stringable
                && $this->classBasename($value::class) === 'StringPrimitive'
            ) {
                $stringValue = (string) $value;
                if (!$client->validateCode($vsUrl, $stringValue)) {
                    $violations[] = $this->violation(
                        'error',
                        $valuePath,
                        sprintf(
                            "Answer '%s' for item '%s' is not a member of value set '%s'.",
                            $stringValue,
                            $linkId,
                            $vsUrl,
                        ),
                    );
                }
            }
        }
    }

    /**
     * unitValueSet membership (M02): when a quantity item carries the questionnaire-unitValueSet
     * extension and a terminology client is configured, the answer Quantity's system+code is
     * validated via validateCoding(). A Quantity with no coded unit (code = '') emits an error
     * because the value set check cannot proceed without one. Returns immediately when no client
     * is wired (backward-compatible, zero-config).
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkUnitValueSetMembership(
        object $item,
        object $questionnaireItem,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        if ($this->terminologyClient === null && $this->clientFactory === null) {
            return;
        }

        $unitVsUrl = $this->itemStringExtension($questionnaireItem, self::EXT_UNIT_VALUE_SET);
        if ($unitVsUrl === null || $unitVsUrl === '') {
            return;
        }

        $client = $this->resolveClientForItem($linkId);

        foreach (array_values($item->answer) as $j => $answer) {
            $value = $answer->value;
            if ($value === null) {
                continue;
            }

            $parts = $this->quantityParts($value);
            if ($parts === null) {
                continue;
            }

            $valuePath = sprintf('%s.answer[%d].value', $path, $j);
            $system    = (string) ($parts['system'] ?? '');
            $code      = (string) ($parts['code'] ?? '');

            if ($code === '') {
                $violations[] = $this->violation(
                    'error',
                    $valuePath,
                    sprintf(
                        "Quantity answer for item '%s' has no unit code; a coded unit is required for value set '%s'.",
                        $linkId,
                        $unitVsUrl,
                    ),
                );

                continue;
            }

            if (!$client->validateCoding($unitVsUrl, $system, $code)) {
                $violations[] = $this->violation(
                    'error',
                    $valuePath,
                    sprintf(
                        "Quantity unit '%s|%s' for item '%s' is not a member of value set '%s'.",
                        $system,
                        $code,
                        $linkId,
                        $unitVsUrl,
                    ),
                );
            }
        }
    }

    /**
     * Resolves the effective terminology client for a given response item linkId. When no
     * factory is configured, returns the default client (or a NullFHIRTerminologyClient when
     * no default client is configured either). When a factory is available, walks the item's
     * ancestor chain and the root Questionnaire to collect preferredTerminologyServer URLs,
     * deduplicating while preserving declaration order; returns a PreferredServerAwareTerminologyClient
     * that tries each preferred URL before falling back to the default.
     */
    private function resolveClientForItem(string $linkId): FHIRTerminologyClientInterface
    {
        $fallback = $this->terminologyClient ?? new NullFHIRTerminologyClient();

        if ($this->clientFactory === null) {
            return $fallback;
        }

        $urls    = [];
        $current = $linkId;

        while ($current !== null && $current !== '') {
            $item = $this->currentItemIndex[$current] ?? null;
            if ($item !== null) {
                foreach ($this->itemCodeExtensions($item, self::EXT_PREFERRED_TERMINOLOGY_SERVER) as $url) {
                    if (!\in_array($url, $urls, true)) {
                        $urls[] = $url;
                    }
                }
            }
            $current = $this->currentParentOf[$current] ?? null;
        }

        if ($this->currentQuestionnaire !== null) {
            foreach ($this->itemCodeExtensions($this->currentQuestionnaire, self::EXT_PREFERRED_TERMINOLOGY_SERVER) as $url) {
                if (!\in_array($url, $urls, true)) {
                    $urls[] = $url;
                }
            }
        }

        if ($urls === []) {
            return $fallback;
        }

        $preferred = array_map(
            fn (string $url): FHIRTerminologyClientInterface => $this->clientFactory->createForServer($url),
            $urls,
        );

        return new PreferredServerAwareTerminologyClient($preferred, $fallback);
    }

    /**
     * Reads the first string-valued item extension matching the given URL; null when absent.
     * Handles both raw string and Stringable extension values (e.g. CanonicalPrimitive).
     *
     * @param R4Item|R4BItem|R5Item $questionnaireItem
     */
    private function itemStringExtension(object $questionnaireItem, string $url): ?string
    {
        foreach ($questionnaireItem->extension as $extension) {
            if ($extension->url !== $url || !isset($extension->value)) {
                continue;
            }

            return $this->extensionString($extension->value);
        }

        return null;
    }

    /**
     * answerOption membership: when the item declares a static answerOption list (and is not the
     * open-choice type, which permits free values), each answer value must match one option. Match
     * uses normalizeValue() identity, so a Coding matches on system+code, numerics compare
     * numerically, and date/time/string compare by value. The optionExclusive extension marks an
     * option that may not be co-selected: choosing it alongside any other answer is a single error.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     * @param R4Item|R4BItem|R5Item                         $questionnaireItem
     * @param list<FHIRValidationViolation>                 $violations
     */
    private function checkAnswerOptionMembership(
        object $item,
        object $questionnaireItem,
        string $type,
        string $linkId,
        string $path,
        array &$violations,
    ): void {
        if ($questionnaireItem->answerOption === [] || $type === 'open-choice') {
            return;
        }

        $permitted = [];
        $exclusive = [];

        foreach ($questionnaireItem->answerOption as $option) {
            $identity = $this->normalizeValue($option->value);
            if ($identity === null) {
                continue;
            }

            $key             = $this->identityKey($identity);
            $permitted[$key] = true;

            if ($this->optionIsExclusive($option)) {
                $exclusive[$key] = true;
            }
        }

        if ($permitted === []) {
            return;
        }

        $answeredKeys = [];

        foreach (array_values($item->answer) as $j => $answer) {
            $identity = $this->normalizeValue($answer->value);
            if ($identity === null) {
                continue;
            }

            $key            = $this->identityKey($identity);
            $answeredKeys[] = $key;

            if (!isset($permitted[$key])) {
                $violations[] = $this->violation(
                    'error',
                    sprintf('%s.answer[%d].value', $path, $j),
                    sprintf("Answer '%s' for item '%s' is not among the permitted answerOption values.", $this->displayValue($answer->value), $linkId),
                );
            }
        }

        if ($exclusive !== [] && \count($answeredKeys) > 1) {
            foreach ($answeredKeys as $key) {
                if (isset($exclusive[$key])) {
                    $violations[] = $this->violation(
                        'error',
                        $path . '.answer',
                        sprintf("Item '%s' selects an exclusive answerOption that cannot be combined with other answers.", $linkId),
                    );
                    break;
                }
            }
        }
    }

    /**
     * Stringifies a normalizeValue() result into a stable array key. Floats render with enough
     * precision to keep distinct option values distinct; bool/string pass through.
     */
    private function identityKey(bool|float|string $identity): string
    {
        if (\is_bool($identity)) {
            return $identity ? 'b:1' : 'b:0';
        }

        if (\is_float($identity)) {
            return 'n:' . rtrim(rtrim(sprintf('%.10F', $identity), '0'), '.');
        }

        return 's:' . $identity;
    }

    /**
     * Whether an answerOption carries the questionnaire-optionExclusive extension set to true.
     *
     * @param R4AnswerOption|R4BAnswerOption|R5AnswerOption $option
     */
    private function optionIsExclusive(object $option): bool
    {
        foreach ($option->extension as $extension) {
            if ($extension->url === self::EXT_OPTION_EXCLUSIVE && isset($extension->value) && $extension->value === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reference target rules: the reference must be a well-formed URL; if it is, its resource type
     * must satisfy the item's constraint — either membership in the questionnaire-referenceResource
     * allow-list when one is declared, or (otherwise) being a known FHIR resource type. A malformed
     * URL short-circuits the type check so a single defect yields a single error.
     *
     * @param AnyReference                  $value
     * @param R4Item|R4BItem|R5Item         $questionnaireItem
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkReferenceAnswer(object $value, object $questionnaireItem, string $linkId, string $path, array &$violations): void
    {
        $reference = $value->reference !== null ? (string) $value->reference : '';
        if ($reference === '') {
            return;
        }

        if (!$this->isWellFormedUri($reference)) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Reference '%s' for item '%s' is not a well-formed URL.", $reference, $linkId),
            );

            return;
        }

        $resourceType = $this->referenceResourceType($reference);
        if ($resourceType === null) {
            return; // contained/urn/opaque reference — no parseable target type
        }

        $allowed = $this->itemCodeExtensions($questionnaireItem, self::EXT_REFERENCE_RESOURCE);

        if ($allowed !== []) {
            if (!\in_array($resourceType, $allowed, true)) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf("Reference target type '%s' for item '%s' is not permitted; allowed: %s.", $resourceType, $linkId, implode(', ', $allowed)),
                );
            }

            return;
        }

        if (!$this->isKnownResourceType($resourceType, $value)) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Reference target type '%s' for item '%s' is not a known FHIR resource type.", $resourceType, $linkId),
            );
        }
    }

    /**
     * Whether a string is a well-formed URI for our purposes: non-empty, free of whitespace,
     * backslash, and control characters (none are permitted by RFC 3986), and — when it is a
     * urn:uuid — carrying a syntactically valid UUID. Relative references and other URN namespaces
     * are accepted.
     */
    private function isWellFormedUri(string $uri): bool
    {
        if ($uri === '') {
            return false;
        }

        if (preg_match('/[\s\\\\\x00-\x1f\x7f]/', $uri) === 1) {
            return false;
        }

        if (str_starts_with(strtolower($uri), 'urn:uuid:')) {
            $uuid = substr($uri, 9);

            return preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid) === 1;
        }

        return true;
    }

    /**
     * Extracts the resource type from a literal reference: the second-to-last non-scheme path
     * segment of `[base/]ResourceType/id[/_history/vid]`. Contained (`#…`) and urn references have
     * no parseable type and return null. Segments containing a colon (the scheme, urn parts) are
     * dropped so both absolute URLs and relative references resolve identically.
     */
    private function referenceResourceType(string $reference): ?string
    {
        if (str_starts_with($reference, '#') || str_starts_with(strtolower($reference), 'urn:')) {
            return null;
        }

        $segments = array_values(array_filter(
            explode('/', $reference),
            static fn (string $segment): bool => $segment !== '' && !str_contains($segment, ':'),
        ));

        $count = \count($segments);
        if ($count < 2) {
            return null;
        }

        return $segments[$count - 2];
    }

    /**
     * Collects the string values of every extension carrying the given URL, in declaration order.
     * Used for multi-valued extensions (referenceResource, mimeType, preferredTerminologyServer).
     *
     * @param AnyItem|AnyQuestionnaire $questionnaireItem
     *
     * @return list<string>
     */
    private function itemCodeExtensions(object $questionnaireItem, string $url): array
    {
        $codes = [];

        foreach ($questionnaireItem->extension as $extension) {
            if ($extension->url !== $url || !isset($extension->value)) {
                continue;
            }

            $code = $this->extensionString($extension->value);
            if ($code !== null && $code !== '') {
                $codes[] = $code;
            }
        }

        return $codes;
    }

    /**
     * Whether a resource type name is defined in the FHIR version matching the answer value, via the
     * generated per-version ResourceType enum. An unrecognised version returns true (never a
     * spurious error).
     *
     * @param AnyReference $exemplar
     */
    private function isKnownResourceType(string $resourceType, object $exemplar): bool
    {
        $known = match (true) {
            $exemplar instanceof R4Reference  => R4ResourceType::tryFrom($resourceType),
            $exemplar instanceof R4BReference => R4BResourceType::tryFrom($resourceType),
            default                           => R5ResourceType::tryFrom($resourceType),
        };

        return $known !== null;
    }

    /**
     * Attachment rules: the answer's contentType must be among the item's mimeType allow-list (when
     * declared); its size must not exceed the maxSize extension (when declared); and a present
     * data+size pair must agree (the decoded byte length equals the declared size). Each is
     * independent so an attachment can trip more than one.
     *
     * @param AnyAttachment                 $value
     * @param R4Item|R4BItem|R5Item         $questionnaireItem
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkAttachmentAnswer(object $value, object $questionnaireItem, string $linkId, string $path, array &$violations): void
    {
        $allowedTypes = $this->itemCodeExtensions($questionnaireItem, self::EXT_MIME_TYPE);
        $contentType  = $value->contentType !== null ? (string) $value->contentType : null;

        if ($allowedTypes !== [] && $contentType !== null && !\in_array($contentType, $allowedTypes, true)) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Attachment content type '%s' for item '%s' is not permitted; allowed: %s.", $contentType, $linkId, implode(', ', $allowedTypes)),
            );
        }

        $size = $value->size !== null ? (int) (string) $value->size : null;

        $maxSize = $this->itemDecimalExtension($questionnaireItem, self::EXT_MAX_SIZE);
        if ($maxSize !== null && $size !== null && $size > $maxSize) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Attachment size %d for item '%s' exceeds the maximum of %s bytes.", $size, $linkId, $this->displayValue($maxSize)),
            );
        }

        if ($value->data !== null && $size !== null) {
            $decoded = base64_decode((string) $value->data, true);
            if ($decoded !== false && \strlen($decoded) !== $size) {
                $violations[] = $this->violation(
                    'error',
                    $path,
                    sprintf("Attachment for item '%s' declares size %d but its data decodes to %d bytes.", $linkId, $size, \strlen($decoded)),
                );
            }
        }
    }

    /**
     * Reads a single decimal-valued item extension (e.g. maxSize) as a float; null when absent or
     * non-numeric.
     *
     * @param R4Item|R4BItem|R5Item $questionnaireItem
     */
    private function itemDecimalExtension(object $questionnaireItem, string $url): ?float
    {
        foreach ($questionnaireItem->extension as $extension) {
            if ($extension->url !== $url || !isset($extension->value)) {
                continue;
            }

            $number = $this->comparableValue($extension->value);

            return \is_float($number) ? $number : null;
        }

        return null;
    }

    /**
     * url primitive format: a `url`-typed answer value must be a well-formed URI. FHIR's url/uri
     * regex (`\S*`) is too permissive to catch the malformed values the corpus exercises, so we add
     * the RFC-3986 character rule (no whitespace/backslash/control) and a urn:uuid format check.
     *
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkUrlFormat(\Stringable $value, string $linkId, string $path, array &$violations): void
    {
        $uri = (string) $value;

        if (!$this->isWellFormedUri($uri)) {
            $violations[] = $this->violation(
                'error',
                $path,
                sprintf("Answer '%s' for item '%s' is not a well-formed URL.", $uri, $linkId),
            );
        }
    }

    /**
     * Evaluates whether an item is enabled per its enableWhen conditions.
     *
     * Conditions referencing a question that is itself disabled see no answers. Cyclic
     * enableWhen chains resolve leniently to enabled. A missing enableBehavior with multiple
     * conditions defaults to 'any' (lenient; the questionnaire violates que-12 in that case).
     *
     * @param R4Item|R4BItem|R5Item $item
     * @param ItemIndex             $itemIndex
     * @param ResponseIndex         $responseIndex
     * @param array<string, true>   $evaluating    linkIds currently being evaluated (cycle guard)
     */
    private function isItemEnabled(object $item, array $itemIndex, array $responseIndex, array &$evaluating): bool
    {
        $expressionResult = $this->evaluateEnableWhenExpression($item);
        if ($expressionResult !== null) {
            return $expressionResult;
        }

        if ($item->enableWhen === []) {
            return true;
        }

        $linkId = $item->linkId !== null ? (string) $item->linkId : '';

        if ($linkId !== '' && isset($evaluating[$linkId])) {
            return true; // cycle — resolve leniently rather than recursing forever
        }

        if ($linkId !== '') {
            $evaluating[$linkId] = true;
        }

        try {
            $results = [];

            foreach ($item->enableWhen as $condition) {
                $results[] = $this->evaluateEnableWhen($condition, $itemIndex, $responseIndex, $evaluating);
            }

            $behavior = $item->enableBehavior !== null ? (string) $item->enableBehavior : 'any';

            return $behavior === 'all'
                ? !\in_array(false, $results, true)
                : \in_array(true, $results, true);
        } finally {
            unset($evaluating[$linkId]);
        }
    }

    /**
     * @param R4Item|R4BItem|R5Item $item
     */
    private function evaluateEnableWhenExpression(object $item): ?bool
    {
        $response = $this->currentResponse;
        if ($response === null) {
            return null;
        }

        $expression = null;
        foreach ($item->extension as $extension) {
            $url = $extension->url;
            if ($url === self::EXT_ENABLE_WHEN_EXPRESSION_KANTA) {
                $value      = isset($extension->value) ? $extension->value : null;
                $expression = $this->extensionString($value);
            } elseif ($url === self::EXT_ENABLE_WHEN_EXPRESSION_SDC) {
                // valueExpression carries the FHIRPath in its nested `expression` string field.
                $valueExpr  = isset($extension->value) ? $extension->value : null;
                $expression = ($valueExpr !== null && isset($valueExpr->expression))
                    ? $this->extensionString($valueExpr->expression)
                    : null;
            } else {
                continue;
            }
            if ($expression !== null) {
                break;
            }
        }

        if ($expression === null || $expression === '') {
            return null;
        }

        try {
            $result = $this->pathService->evaluate($expression, $response);
        } catch (FHIRPathException) {
            return null; // DEFER-not-DENY: treat as enabled on evaluation failure
        }

        foreach ($result as $value) {
            if ($value === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param R4EnableWhen|R4BEnableWhen|R5EnableWhen $condition
     * @param ItemIndex                               $itemIndex
     * @param ResponseIndex                           $responseIndex
     * @param array<string, true>                     $evaluating
     */
    private function evaluateEnableWhen(
        object $condition,
        array $itemIndex,
        array $responseIndex,
        array &$evaluating,
    ): bool {
        $question = $condition->question !== null ? (string) $condition->question : '';
        $operator = $condition->operator !== null ? (string) $condition->operator : '';

        $answers = $this->answersForQuestion($question, $itemIndex, $responseIndex, $evaluating);

        if ($operator === 'exists') {
            $expected = \is_bool($condition->answer) ? $condition->answer : true;

            return ($answers !== []) === $expected;
        }

        if ($answers === [] || $condition->answer === null) {
            return false;
        }

        foreach ($answers as $value) {
            if ($this->compareValues($value, $condition->answer, $operator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Collects the answer values given for a question linkId. A question that is itself
     * disabled contributes no answers.
     *
     * @param ItemIndex           $itemIndex
     * @param ResponseIndex       $responseIndex
     * @param array<string, true> $evaluating
     *
     * @return list<mixed>
     */
    private function answersForQuestion(
        string $question,
        array $itemIndex,
        array $responseIndex,
        array &$evaluating,
    ): array {
        if ($question === '') {
            return [];
        }

        $questionItem = $itemIndex[$question] ?? null;

        if ($questionItem !== null && !$this->isItemEnabled($questionItem, $itemIndex, $responseIndex, $evaluating)) {
            return [];
        }

        $values = [];

        foreach ($responseIndex[$question] ?? [] as $entry) {
            foreach ($entry['item']->answer as $answer) {
                if ($answer->value !== null) {
                    $values[] = $answer->value;
                }
            }
        }

        return $values;
    }

    private function compareValues(mixed $actual, mixed $expected, string $operator): bool
    {
        if ($operator === '=' || $operator === '!=') {
            $a = $this->normalizeValue($actual);
            $b = $this->normalizeValue($expected);

            if ($a === null || $b === null) {
                return false; // incomparable operands satisfy neither '=' nor '!=' — never enable on the unknown
            }

            return $operator === '=' ? $a === $b : $a !== $b;
        }

        $a = $this->comparableValue($actual);
        $b = $this->comparableValue($expected);

        if (\is_float($a) && \is_float($b)) {
            $cmp = $a <=> $b;
        } elseif (\is_string($a) && \is_string($b)) {
            $cmp = strcmp($a, $b);
        } else {
            return false; // incomparable operand types
        }

        return match ($operator) {
            '>'     => $cmp > 0,
            '<'     => $cmp < 0,
            '>='    => $cmp >= 0,
            '<='    => $cmp <= 0,
            default => false,
        };
    }

    /**
     * Rule 3 — required, enabled children of one parent instance must be satisfied within
     * that instance. Per the specification, required "only has meaning if the parent element
     * is present": absent optional parents exempt their children, and each instance of a
     * repeating parent is checked independently. A required question needs a direct answer;
     * a required group needs at least one answered descendant question.
     *
     * Grandchildren are NOT recursed into here — they are checked when their own parent
     * instance is walked, which is what makes the check instance-scoped.
     *
     * @param array<R4Item|R4BItem|R5Item>                        $questionnaireChildren
     * @param list<R4ResponseItem|R4BResponseItem|R5ResponseItem> $responseChildItems
     * @param ItemIndex                                           $itemIndex
     * @param ResponseIndex                                       $responseIndex
     * @param list<FHIRValidationViolation>                       $violations
     */
    private function checkRequiredChildren(
        array $questionnaireChildren,
        array $responseChildItems,
        string $instancePath,
        array $itemIndex,
        array $responseIndex,
        array &$violations,
    ): void {
        $byLinkId = [];

        foreach ($responseChildItems as $child) {
            $childLinkId = $child->linkId !== null ? (string) $child->linkId : '';

            if ($childLinkId !== '') {
                $byLinkId[$childLinkId][] = $child;
            }
        }

        foreach ($questionnaireChildren as $child) {
            $linkId = $child->linkId !== null ? (string) $child->linkId : '';

            if ($linkId === '' || $child->required !== true) {
                continue;
            }

            $evaluating = [];
            if (!$this->isItemEnabled($child, $itemIndex, $responseIndex, $evaluating)) {
                continue;
            }

            $occurrences = $byLinkId[$linkId] ?? [];

            if (($child->type !== null ? (string) $child->type : '') === 'group') {
                $satisfied = false;

                foreach ($occurrences as $occurrence) {
                    if ($this->hasAnsweredDescendant($occurrence)) {
                        $satisfied = true;
                        break;
                    }
                }

                if (!$satisfied) {
                    $violations[] = $this->violation(
                        'error',
                        $instancePath,
                        $occurrences === []
                            ? sprintf("Required group '%s' is missing.", $linkId)
                            : sprintf("Required group '%s' has no answered descendant question.", $linkId),
                    );
                }
            } else {
                $satisfied = false;

                foreach ($occurrences as $occurrence) {
                    if ($this->hasAnswerValue($occurrence)) {
                        $satisfied = true;
                        break;
                    }
                }

                if (!$satisfied) {
                    $violations[] = $this->violation(
                        'error',
                        $instancePath,
                        sprintf("Required item '%s' has no answer.", $linkId),
                    );
                }
            }
        }
    }

    /**
     * Whether any descendant of this response item carries an answer — the spec's
     * satisfaction criterion for a required group.
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     */
    private function hasAnsweredDescendant(object $item): bool
    {
        foreach ($this->collectChildResponseItems($item) as $child) {
            if ($this->hasAnswerValue($child) || $this->hasAnsweredDescendant($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Collects one response item's direct children from both nesting forms: item.item and
     * item.answer[*].item (the latter carries children of answered question items).
     *
     * @param R4ResponseItem|R4BResponseItem|R5ResponseItem $item
     *
     * @return list<R4ResponseItem|R4BResponseItem|R5ResponseItem>
     */
    private function collectChildResponseItems(object $item): array
    {
        $children = array_values($item->item);

        foreach ($item->answer as $answer) {
            foreach ($answer->item as $child) {
                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * Warns about enableWhen conditions referencing linkIds that do not exist in the
     * Questionnaire. Checked once per questionnaire item, independent of the response.
     *
     * @param ItemIndex                     $itemIndex
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkEnableWhenReferences(array $itemIndex, array &$violations): void
    {
        foreach ($itemIndex as $linkId => $item) {
            foreach (array_values($item->enableWhen) as $c => $condition) {
                $question = $condition->question !== null ? (string) $condition->question : '';

                if ($question !== '' && !isset($itemIndex[$question])) {
                    $violations[] = $this->violation(
                        'warning',
                        sprintf('Questionnaire.item[linkId=%s].enableWhen[%d].question', $linkId, $c),
                        sprintf(
                            "enableWhen.question '%s' on item '%s' does not reference a known linkId.",
                            $question,
                            $linkId,
                        ),
                    );
                }
            }
        }
    }
}
