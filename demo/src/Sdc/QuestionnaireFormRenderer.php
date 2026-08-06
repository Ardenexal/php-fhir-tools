<?php

declare(strict_types=1);

namespace App\Sdc;

/**
 * Builds the render model {@see SdcController} hands to `sdc/index.html.twig`: a flat-or-grouped tree
 * of form fields derived from a Questionnaire's items, seedable from either a prior POST's raw answers
 * (validation redisplay) or a `$populate` result's `QuestionnaireResponse` (prefill) — both paths funnel
 * through {@see QuestionnaireItemCodec} so the two directions can't drift apart.
 *
 * The render model uses the *same* field-naming scheme `QuestionnaireResponseBuilder` expects on the way
 * back in: `answers[<linkId>]` for a leaf, `answers[<groupLinkId>][<childLinkId>]` for a nested group.
 *
 * `item.repeats === true` fields carry a *list* of values/instances (one per repeat row) instead of a
 * single value/children-list — a non-repeating field is simply a repeating field with exactly one,
 * non-removable row. This uniform shape is what lets the Twig macro iterate `values`/`instances`
 * regardless of `repeats`, and what fixes the M02-era bug where a repeating group's multiple
 * `QuestionnaireResponse.item` entries sharing one `linkId` collapsed into a single rendered row: the QR
 * side is indexed as `linkId => list<object>` (never last-write-wins) precisely so every instance
 * survives the round trip.
 *
 * `enableWhen` is scoped to its M03 fixed grammar: only the item's FIRST `enableWhen` entry is rendered
 * (`enableBehavior: all`/`any` across multiple conditions is not supported — restrict fixtures to a
 * single condition per item). The condition is passed through as a small `{question, operator, answerX}`
 * array for the client-side Stimulus controller to evaluate; this class does no visibility logic itself
 * (`enableWhen` is a display-time concern, evaluated in the browser with no server round-trip).
 *
 * `answerValueSet`-bound `choice` items (no `answerOption` list — the library has no `ValueSet/$expand`
 * support, so a live-populated dropdown isn't possible; see M04's assumption) render `hasAnswerValueSet:
 * true` instead of populated `options`, so the template shows a free-text code input plus a "Check code"
 * action (validated server-side against the configured terminology server) rather than an empty `<select>`.
 *
 * @phpstan-type RenderField array{
 *     kind: 'field'|'group'|'display',
 *     linkId: string,
 *     text: string,
 *     type: string,
 *     repeats: bool,
 *     options: list<array{index: string, label: string}>,
 *     hasAnswerValueSet: bool,
 *     values: list<string>,
 *     instances: list<list<mixed>>,
 *     enableWhen: array<string, mixed>|null,
 * }
 */
final class QuestionnaireFormRenderer
{
    public function __construct(
        private readonly QuestionnaireItemCodec $codec = new QuestionnaireItemCodec(),
    ) {
    }

    /**
     * Render fresh (no prior answers) — every value starts blank; each repeating field/group starts
     * with exactly one blank row so there is something to "add another" from.
     *
     * @param list<array<string, mixed>> $questionnaireItems
     *
     * @return list<array<string, mixed>>
     */
    public function renderBlank(array $questionnaireItems): array
    {
        return $this->renderFromAnswers($questionnaireItems, []);
    }

    /**
     * Redisplay a prior submission's raw posted answers (e.g. after a validation error) — no codec
     * translation needed, the posted strings already are form values. Repeat rows come from whatever
     * indices were posted (in posted order); an absent/empty scope falls back to one blank row.
     *
     * @param list<array<string, mixed>> $questionnaireItems
     * @param array<string, mixed>       $answers
     *
     * @return list<array<string, mixed>>
     */
    public function renderFromAnswers(array $questionnaireItems, array $answers): array
    {
        $fields = [];

        foreach ($questionnaireItems as $item) {
            $linkId  = (string) ($item['linkId'] ?? '');
            $type    = (string) ($item['type'] ?? 'string');
            $text    = (string) ($item['text'] ?? $linkId);
            $repeats = ($item['repeats'] ?? false) === true;

            if ($type === 'display') {
                $fields[] = $this->displayField($linkId, $text, $type);

                continue;
            }

            if ($type === 'group') {
                /** @var list<array<string, mixed>> $children */
                $children       = \is_array($item['item'] ?? null) ? $item['item'] : [];
                $groupScope     = $answers[$linkId] ?? null;
                $instanceScopes = $this->answerInstanceScopes($groupScope, $repeats);

                $instances = [];
                foreach ($instanceScopes as $instanceScope) {
                    $instances[] = $this->renderFromAnswers($children, \is_array($instanceScope) ? $instanceScope : []);
                }

                $fields[] = $this->groupField($linkId, $text, $type, $repeats, $instances, $this->renderEnableWhen($item));

                continue;
            }

            $leafScope = $answers[$linkId] ?? null;
            $values    = [];
            foreach ($this->answerInstanceScopes($leafScope, $repeats) as $instanceValue) {
                $values[] = \is_string($instanceValue) ? $instanceValue : '';
            }

            $fields[] = $this->leafField($linkId, $text, $type, $repeats, $values, $this->renderOptions($item), $this->hasAnswerValueSet($item), $this->renderEnableWhen($item));
        }

        return $fields;
    }

    /**
     * Prefill from a `$populate` result. Walks the QR's items, indexed `linkId => list<object>` so
     * repeated instances all survive (never last-write-wins), using `??` isset-semantics reads
     * throughout — bare access throws on uninitialized typed properties per the
     * `model-object-initialization` footgun.
     *
     * @param list<array<string, mixed>> $questionnaireItems
     *
     * @return list<array<string, mixed>>
     */
    public function renderFromResponse(array $questionnaireItems, object $response): array
    {
        $qrItems = \is_array($response->item ?? null) ? $response->item : [];

        return $this->renderFromQrItems($questionnaireItems, $qrItems);
    }

    /**
     * @param list<array<string, mixed>> $questionnaireItems
     * @param list<object>               $qrItemsAtThisLevel QR items available at this nesting level
     *                                                       (siblings — not yet indexed by linkId)
     *
     * @return list<array<string, mixed>>
     */
    private function renderFromQrItems(array $questionnaireItems, array $qrItemsAtThisLevel): array
    {
        $byLinkId = $this->groupQrItemsByLinkId($qrItemsAtThisLevel);
        $fields   = [];

        foreach ($questionnaireItems as $item) {
            $linkId  = (string) ($item['linkId'] ?? '');
            $type    = (string) ($item['type'] ?? 'string');
            $text    = (string) ($item['text'] ?? $linkId);
            $repeats = ($item['repeats'] ?? false) === true;
            $matches = $byLinkId[$linkId] ?? [];

            if ($type === 'display') {
                $fields[] = $this->displayField($linkId, $text, $type);

                continue;
            }

            if ($type === 'group') {
                /** @var list<array<string, mixed>> $children */
                $children = \is_array($item['item'] ?? null) ? $item['item'] : [];

                $instanceSources = $matches !== [] ? $matches : [null];
                if (!$repeats) {
                    $instanceSources = [$matches[0] ?? null];
                }

                $instances = [];
                foreach ($instanceSources as $qrGroupItem) {
                    $childQrItems = $qrGroupItem !== null && \is_array($qrGroupItem->item ?? null) ? $qrGroupItem->item : [];
                    $instances[]  = $this->renderFromQrItems($children, $childQrItems);
                }

                $fields[] = $this->groupField($linkId, $text, $type, $repeats, $instances, $this->renderEnableWhen($item));

                continue;
            }

            $values  = [];
            $sources = $repeats ? $matches : ($matches !== [] ? [$matches[0]] : []);
            foreach ($sources as $qrItem) {
                $answers = \is_array($qrItem->answer ?? null) ? $qrItem->answer : [];
                foreach ($answers as $answer) {
                    $values[] = $this->codec->toFormValue($item, $answer->value ?? null);
                }
            }
            if ($values === []) {
                $values = [''];
            }

            $fields[] = $this->leafField($linkId, $text, $type, $repeats, $values, $this->renderOptions($item), $this->hasAnswerValueSet($item), $this->renderEnableWhen($item));
        }

        return $fields;
    }

    /**
     * Normalize a posted answers scope (for a group or leaf linkId) into a list of per-instance scopes:
     * a repeating item's scope is already `index => instance` (iterated as posted — sparse/non-numeric
     * keys survive intact); a non-repeating item's scope is wrapped as the single instance; an
     * absent/empty scope yields exactly one blank instance so there is always a row to render.
     *
     * @return list<mixed>
     */
    private function answerInstanceScopes(mixed $scope, bool $repeats): array
    {
        if (!$repeats) {
            return [$scope];
        }

        if (\is_array($scope) && $scope !== []) {
            return array_values($scope);
        }

        return [null];
    }

    /**
     * Index QR items by `linkId => list<object>` (never last-write-wins) so a repeating group/leaf's
     * multiple entries sharing one linkId all survive for the renderer to walk.
     *
     * @param list<object> $qrItems
     *
     * @return array<string, list<object>>
     */
    private function groupQrItemsByLinkId(array $qrItems): array
    {
        $index = [];

        foreach ($qrItems as $qrItem) {
            $linkId = $qrItem->linkId ?? null;
            if (\is_string($linkId) && $linkId !== '') {
                $index[$linkId][] = $qrItem;
            }
        }

        return $index;
    }

    /** @return array<string, mixed> */
    private function displayField(string $linkId, string $text, string $type): array
    {
        return ['kind' => 'display', 'linkId' => $linkId, 'text' => $text, 'type' => $type, 'repeats' => false, 'options' => [], 'hasAnswerValueSet' => false, 'values' => [], 'instances' => [], 'enableWhen' => null];
    }

    /**
     * @param list<string>                              $values
     * @param list<array{index: string, label: string}> $options
     * @param array<string, mixed>|null                 $enableWhen
     *
     * @return array<string, mixed>
     */
    private function leafField(string $linkId, string $text, string $type, bool $repeats, array $values, array $options, bool $hasAnswerValueSet, ?array $enableWhen): array
    {
        return ['kind' => 'field', 'linkId' => $linkId, 'text' => $text, 'type' => $type, 'repeats' => $repeats, 'options' => $options, 'hasAnswerValueSet' => $hasAnswerValueSet, 'values' => $values, 'instances' => [], 'enableWhen' => $enableWhen];
    }

    /**
     * @param list<list<mixed>>         $instances
     * @param array<string, mixed>|null $enableWhen
     *
     * @return array<string, mixed>
     */
    private function groupField(string $linkId, string $text, string $type, bool $repeats, array $instances, ?array $enableWhen): array
    {
        return ['kind' => 'group', 'linkId' => $linkId, 'text' => $text, 'type' => $type, 'repeats' => $repeats, 'options' => [], 'hasAnswerValueSet' => false, 'values' => [], 'instances' => $instances, 'enableWhen' => $enableWhen];
    }

    /** @param array<string, mixed> $item */
    private function hasAnswerValueSet(array $item): bool
    {
        if (($item['type'] ?? null) !== 'choice') {
            return false;
        }

        $options = $item['answerOption'] ?? null;
        if (\is_array($options) && $options !== []) {
            return false;
        }

        return \is_string($item['answerValueSet'] ?? null) && $item['answerValueSet'] !== '';
    }

    /**
     * Extract the first `enableWhen` condition (M03's fixed-grammar, single-condition scope) as a small
     * `{question, operator, answerX}` array for the client to evaluate, or null when the item declares
     * none.
     *
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>|null
     */
    private function renderEnableWhen(array $item): ?array
    {
        $conditions = $item['enableWhen'] ?? null;
        if (!\is_array($conditions) || $conditions === []) {
            return null;
        }

        $first = $conditions[0] ?? null;

        return \is_array($first) ? $first : null;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return list<array{index: string, label: string}>
     */
    private function renderOptions(array $item): array
    {
        if (($item['type'] ?? null) !== 'choice') {
            return [];
        }

        $options  = \is_array($item['answerOption'] ?? null) ? $item['answerOption'] : [];
        $rendered = [];

        foreach ($options as $index => $option) {
            if (!\is_array($option)) {
                continue;
            }

            $label = match (true) {
                isset($option['valueCoding']['display']) => (string) $option['valueCoding']['display'],
                isset($option['valueCoding']['code'])    => (string) $option['valueCoding']['code'],
                isset($option['valueString'])            => (string) $option['valueString'],
                default                                  => (string) $index,
            };

            $rendered[] = ['index' => (string) $index, 'label' => $label];
        }

        return $rendered;
    }
}
