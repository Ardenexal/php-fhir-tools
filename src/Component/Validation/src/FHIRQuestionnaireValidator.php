<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding as R4Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity as R4Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference as R4Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem as R4Item;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen as R4EnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource as R4Questionnaire;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4ResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4Answer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource as R4Response;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding as R4BCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity as R4BQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference as R4BReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItem as R4BItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItemEnableWhen as R4BEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource as R4BQuestionnaire;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4BResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4BAnswer;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponseResource as R4BResponse;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding as R5Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity as R5Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference as R5Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem as R5Item;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemEnableWhen as R5EnableWhen;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource as R5Questionnaire;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R5ResponseItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R5Answer;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResponseResource as R5Response;

/**
 * Validates a QuestionnaireResponse against its source Questionnaire (R4, R4B, and R5).
 *
 * Implements the five core structural rules from the FHIR specification:
 *  1. linkId match — every response item linkId must exist in the source Questionnaire (error)
 *  2. required items — items with required=true must be answered when the response status is
 *     completed or amended and the item is enabled (error)
 *  3. repeats — non-repeating items must occur at most once and carry at most one answer (error)
 *  4. answer type — the answer value type must match the declared item type (warning)
 *  5. enableWhen — items present while their enableWhen conditions are unsatisfied (warning)
 *
 * Type dispatch uses raw code strings, never the generated QuestionnaireItemType enum — the
 * enum only contains the three hierarchy-root codes (see footgun generated-enum-hierarchy-gap).
 *
 * @phpstan-type AnyQuestionnaire R4Questionnaire|R4BQuestionnaire|R5Questionnaire
 * @phpstan-type AnyResponse R4Response|R4BResponse|R5Response
 * @phpstan-type AnyItem R4Item|R4BItem|R5Item
 * @phpstan-type AnyResponseItem R4ResponseItem|R4BResponseItem|R5ResponseItem
 * @phpstan-type AnyAnswer R4Answer|R4BAnswer|R5Answer
 * @phpstan-type AnyEnableWhen R4EnableWhen|R4BEnableWhen|R5EnableWhen
 * @phpstan-type ResponseIndex array<string, list<array{item: R4ResponseItem|R4BResponseItem|R5ResponseItem, path: string}>>
 * @phpstan-type ItemIndex array<string, R4Item|R4BItem|R5Item>
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

        $itemIndex = [];
        $this->indexQuestionnaireItems($questionnaire->item, $itemIndex);

        $responseIndex = [];
        $this->indexResponseItems($response->item, 'item', $responseIndex);

        $violations = [];

        $this->walkResponseItems($response->item, 'item', $itemIndex, $responseIndex, $violations);
        $this->checkEnableWhenReferences($itemIndex, $violations);

        if ($strictStatus && \in_array((string) ($response->status ?? ''), self::ANSWERABLE_STATUSES, true)) {
            $this->checkRequiredItems($questionnaire->item, $itemIndex, $responseIndex, $violations);
        }

        return new FHIRValidationReport($violations);
    }

    /**
     * Builds a flat linkId → item index by walking the questionnaire item tree.
     *
     * linkIds are spec-unique within a Questionnaire (invariant que-2), so a flat map suffices.
     * Items with a null linkId are skipped; base validation reports the missing required field.
     *
     * @param array<R4Item|R4BItem|R5Item>         $items
     * @param array<string, R4Item|R4BItem|R5Item> $index
     */
    private function indexQuestionnaireItems(array $items, array &$index): void
    {
        foreach ($items as $item) {
            if ($item->linkId !== null && (string) $item->linkId !== '') {
                $index[(string) $item->linkId] = $item;
            }

            $this->indexQuestionnaireItems($item->item, $index);
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
     * Walks the response item tree applying rules 1 (linkId match), 3b (answer cardinality),
     * 4 (answer type), and 5 (item present while disabled).
     *
     * @param array<R4ResponseItem|R4BResponseItem|R5ResponseItem> $items
     * @param ItemIndex                                            $itemIndex
     * @param ResponseIndex                                        $responseIndex
     * @param list<FHIRValidationViolation>                        $violations
     */
    private function walkResponseItems(
        array $items,
        string $pathPrefix,
        array $itemIndex,
        array $responseIndex,
        array &$violations,
    ): void {
        $this->checkSiblingOccurrences($items, $pathPrefix, $itemIndex, $violations);

        foreach (array_values($items) as $i => $item) {
            $path   = sprintf('%s[%d]', $pathPrefix, $i);
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($linkId !== '') {
                $questionnaireItem = $itemIndex[$linkId] ?? null;

                if ($questionnaireItem === null) {
                    $violations[] = $this->violation(
                        'error',
                        $path . '.linkId',
                        sprintf("Response item linkId '%s' does not exist in the source Questionnaire.", $linkId),
                    );
                } else {
                    $this->checkAnswerCardinality($item, $questionnaireItem, $linkId, $path, $violations);
                    $this->checkAnswerTypes($item, $questionnaireItem, $linkId, $path, $violations);

                    $evaluating = [];
                    if (!$this->isItemEnabled($questionnaireItem, $itemIndex, $responseIndex, $evaluating)) {
                        $violations[] = $this->violation(
                            'warning',
                            $path,
                            sprintf("Item '%s' is present but its enableWhen conditions are not satisfied.", $linkId),
                        );
                    }
                }
            }

            $this->walkResponseItems($item->item, $path . '.item', $itemIndex, $responseIndex, $violations);

            foreach (array_values($item->answer) as $j => $answer) {
                $this->walkResponseItems(
                    $answer->item,
                    sprintf('%s.answer[%d].item', $path, $j),
                    $itemIndex,
                    $responseIndex,
                    $violations,
                );
            }
        }
    }

    /**
     * Rule 3b — a non-repeating item must carry at most one answer.
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
        array &$violations,
    ): void {
        $answerCount = \count($item->answer);

        if ($questionnaireItem->repeats !== true && $answerCount > 1) {
            $violations[] = $this->violation(
                'error',
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
     * Rule 4 — the answer value type must match the declared item type (warning severity:
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
        }
    }

    /**
     * Rule 3a — a non-repeating item must not occur more than once among its siblings.
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
            foreach ($item->enableWhen as $condition) {
                $question = $condition->question !== null ? (string) $condition->question : '';

                if ($question !== '' && !isset($itemIndex[$question])) {
                    $violations[] = $this->violation(
                        'warning',
                        'item',
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

    /**
     * Rule 2 — required, enabled items must be answered. Walks the questionnaire tree so that
     * entire subtrees under a disabled parent are exempt. Groups are satisfied by presence;
     * questions require at least one answer.
     *
     * @param array<R4Item|R4BItem|R5Item>  $items
     * @param ItemIndex                     $itemIndex
     * @param ResponseIndex                 $responseIndex
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkRequiredItems(
        array $items,
        array $itemIndex,
        array $responseIndex,
        array &$violations,
    ): void {
        foreach ($items as $item) {
            $evaluating = [];
            if (!$this->isItemEnabled($item, $itemIndex, $responseIndex, $evaluating)) {
                continue; // disabled subtree: neither this item nor its children are required
            }

            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($item->required === true && $linkId !== '') {
                $entries = $responseIndex[$linkId] ?? [];
                $type    = $item->type !== null ? (string) $item->type : '';

                $satisfied = $type === 'group'
                    ? $entries !== []
                    : $this->hasAnyAnswer($entries);

                if (!$satisfied) {
                    $violations[] = $this->violation(
                        'error',
                        'item',
                        sprintf("Required item '%s' has no answer.", $linkId),
                    );
                }
            }

            $this->checkRequiredItems($item->item, $itemIndex, $responseIndex, $violations);
        }
    }

    /**
     * @param list<array{item: R4ResponseItem|R4BResponseItem|R5ResponseItem, path: string}> $entries
     */
    private function hasAnyAnswer(array $entries): bool
    {
        foreach ($entries as $entry) {
            if ($entry['item']->answer !== []) {
                return true;
            }
        }

        return false;
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

            $equal = $a !== null && $b !== null && $a === $b;

            return $operator === '=' ? $equal : !$equal;
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

    private function classBasename(string $fqcn): string
    {
        $position = strrpos($fqcn, '\\');

        return $position === false ? $fqcn : substr($fqcn, $position + 1);
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
}
