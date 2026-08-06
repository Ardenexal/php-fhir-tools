<?php

declare(strict_types=1);

namespace App\Sdc;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;

/**
 * Reconstructs a `QuestionnaireResponse` model directly from posted form answers plus the source
 * Questionnaire's item tree (no JSON round-trip — the object graph is built by hand, mirroring M01's
 * spike). Generic over non-repeating `string`/`text`/`boolean`/`integer`/`decimal`/`date`/`dateTime`/
 * `choice`/`group`/`display` items, keyed by the nested field-naming scheme
 * `answers[<linkId>]` / `answers[<groupLinkId>][<childLinkId>]`.
 *
 * Group items are detected by `item.type === 'group'` (never by "has children" — a `display` item can
 * also carry no answer but is not a group) and are emitted as a `QuestionnaireResponse.item` even when
 * every descendant answer is blank... UNLESS the whole subtree is blank, in which case the group is
 * pruned entirely (an empty group would otherwise extract into a bare `POST <ResourceType>` entry with
 * no content). A group with at least one answered descendant is always emitted in full, including its
 * unanswered leaf items, since `DefinitionExtractionWalker` matches children by `linkId` against the
 * *Questionnaire's* item tree, not the QR's.
 *
 * `item.repeats === true` (checked on the *Questionnaire* item, never inferred from the posted data's
 * shape) changes both the field naming and the QR shape it produces:
 *  - a repeating **leaf** (`answers[<linkId>][<index>]`) produces ONE `QuestionnaireResponseItem` with
 *    multiple `answer[]` entries — not multiple items.
 *  - a repeating **group** (`answers[<groupLinkId>][<index>][<childLinkId>]`) produces ONE
 *    `QuestionnaireResponseItem` PER index, each with its own children.
 * Indices are iterated as posted, not assumed contiguous — removing a middle row client-side leaves a
 * sparse index set (e.g. `0`, `2`) that must survive intact, not collapse to a truncated prefix.
 */
final class QuestionnaireResponseBuilder
{
    public function __construct(
        private readonly QuestionnaireItemCodec $codec = new QuestionnaireItemCodec(),
    ) {
    }

    /**
     * @param list<array<string, mixed>> $questionnaireItems the source Questionnaire's top-level items
     * @param array<string, mixed>       $answers            posted `answers` nested array
     */
    public function build(array $questionnaireItems, array $answers): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: $this->buildItems($questionnaireItems, $answers),
        );
    }

    /**
     * @param list<array<string, mixed>> $questionnaireItems
     * @param array<string, mixed>       $answers
     *
     * @return list<QuestionnaireResponseItem>
     */
    private function buildItems(array $questionnaireItems, array $answers): array
    {
        $responseItems = [];

        foreach ($questionnaireItems as $item) {
            $linkId   = (string) ($item['linkId'] ?? '');
            $type     = (string) ($item['type'] ?? 'string');
            $repeats  = ($item['repeats'] ?? false) === true;

            if ($type === 'display') {
                continue;
            }

            if ($type === 'group') {
                /** @var list<array<string, mixed>> $children */
                $children = \is_array($item['item'] ?? null) ? $item['item'] : [];

                if ($repeats) {
                    /** @var array<array-key, mixed> $instances */
                    $instances = \is_array($answers[$linkId] ?? null) ? $answers[$linkId] : [];
                    foreach ($instances as $instanceAnswers) {
                        if (!\is_array($instanceAnswers)) {
                            continue;
                        }
                        $childItems = $this->buildItems($children, $instanceAnswers);
                        if ($childItems === []) {
                            continue;
                        }
                        $responseItems[] = new QuestionnaireResponseItem(linkId: $linkId, item: $childItems);
                    }

                    continue;
                }

                /** @var array<string, mixed> $childAnswers */
                $childAnswers = \is_array($answers[$linkId] ?? null) ? $answers[$linkId] : [];
                $childItems   = $this->buildItems($children, $childAnswers);

                if ($childItems === []) {
                    // No answered descendant anywhere in this subtree — prune the whole group rather
                    // than extracting an empty resource.
                    continue;
                }

                $responseItems[] = new QuestionnaireResponseItem(linkId: $linkId, item: $childItems);

                continue;
            }

            if ($repeats) {
                /** @var array<array-key, mixed> $instances */
                $instances     = \is_array($answers[$linkId] ?? null) ? $answers[$linkId] : [];
                $repeatAnswers = [];
                foreach ($instances as $rawInstanceAnswer) {
                    if (!\is_string($rawInstanceAnswer)) {
                        continue;
                    }
                    $value = $this->codec->fromFormValue($item, $rawInstanceAnswer);
                    if ($value !== null) {
                        $repeatAnswers[] = new QuestionnaireResponseItemAnswer(value: $value);
                    }
                }

                if ($repeatAnswers === []) {
                    continue;
                }

                $responseItems[] = new QuestionnaireResponseItem(linkId: $linkId, answer: $repeatAnswers);

                continue;
            }

            $rawAnswer = $answers[$linkId] ?? null;
            if (!\is_string($rawAnswer)) {
                continue;
            }

            $value = $this->codec->fromFormValue($item, $rawAnswer);
            if ($value === null) {
                continue;
            }

            $responseItems[] = new QuestionnaireResponseItem(
                linkId: $linkId,
                answer: [new QuestionnaireResponseItemAnswer(value: $value)],
            );
        }

        return $responseItems;
    }
}
