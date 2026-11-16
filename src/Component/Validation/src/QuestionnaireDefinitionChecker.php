<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports structural mistakes in a Questionnaire's own item tree.
 *
 * {@see FHIRQuestionnaireValidator} checks a QuestionnaireResponse against its Questionnaire and so
 * only ever sees a Questionnaire as a source of rules. The rules here are about the Questionnaire
 * as a resource being validated, so they run in {@see FHIRValidationService} on any Questionnaire it
 * meets, including one nested in a Bundle or `contained`.
 *
 * ## enableWhen on an inner question
 *
 * An item's `enableWhen.question` must not name one of that item's own descendants. A descendant
 * can only be answered while the item is enabled, so the condition would depend on its own outcome. The reference validator reports it as an error on the item carrying the `enableWhen`,
 * in the wording used here.
 */
final class QuestionnaireDefinitionChecker
{
    public const string INNER_QUESTION_MESSAGE = 'Questions with an enableWhen cannot refer to an inner question for its enableWhen condition';

    /** @var array<class-string, bool> keyed by class name */
    private array $isQuestionnaire = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Walk a resource, find every Questionnaire in it, and check each one's items.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited    = [];
        $violations = [];
        $this->walk($resource, '', $visited, $violations);

        return $violations;
    }

    /**
     * @param array<int, true>              $visited    spl_object_id keys of the objects on the current descent (cycle guard)
     * @param list<FHIRValidationViolation> $violations collects the findings
     */
    private function walk(object $node, string $path, array &$visited, array &$violations): void
    {
        $objectId = spl_object_id($node);

        if (isset($visited[$objectId])) {
            return;
        }

        $visited[$objectId] = true;

        if ($this->isQuestionnaire($node)) {
            $itemVisited = [];
            $this->checkItems($this->itemsOf($node), $path === '' ? 'item' : $path . '.item', $itemVisited, $violations);
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                $this->walk($value, $subPath, $visited, $violations);
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $this->walk($item, $subPath . '[' . $i . ']', $visited, $violations);
                    }
                }
            }
        }

        unset($visited[$objectId]);
    }

    /**
     * Check each item at one level, then the levels beneath it.
     *
     * @param array<int|string, object>     $items
     * @param array<int, true>              $visited    spl_object_id keys of the items on the current descent
     *                                                  (cycle guard); an item leaves it once its subtree is checked
     * @param list<FHIRValidationViolation> $violations
     */
    private function checkItems(array $items, string $path, array &$visited, array &$violations): void
    {
        foreach ($items as $i => $item) {
            $objectId = spl_object_id($item);

            if (isset($visited[$objectId])) {
                continue;
            }

            $visited[$objectId] = true;
            $itemPath           = $path . '[' . $i . ']';
            $children           = $this->itemsOf($item);

            if ($children !== [] && $this->enableWhenQuestions($item) !== []) {
                $inner        = [];
                $innerVisited = [$objectId => true];
                $this->collectLinkIds($children, $inner, $innerVisited);

                foreach ($this->enableWhenQuestions($item) as $question) {
                    if (isset($inner[$question])) {
                        $violations[] = new FHIRValidationViolation(
                            severity: 'error',
                            path: $itemPath,
                            message: self::INNER_QUESTION_MESSAGE,
                            constraintClass: self::class,
                            profileGroup: null,
                            invariantKey: null,
                        );

                        break;
                    }
                }
            }

            $this->checkItems($children, $itemPath . '.item', $visited, $violations);

            unset($visited[$objectId]);
        }
    }

    /**
     * @param array<int|string, object> $items
     * @param array<string, true>       $linkIds collects every linkId at or below these items
     * @param array<int, true>          $visited spl_object_id keys of the items already collected (cycle guard)
     */
    private function collectLinkIds(array $items, array &$linkIds, array &$visited): void
    {
        foreach ($items as $item) {
            $objectId = spl_object_id($item);

            if (isset($visited[$objectId])) {
                continue;
            }

            $visited[$objectId] = true;
            $linkId             = $this->stringValue($this->models->readInitializedValue($item, 'linkId'));

            if ($linkId !== null) {
                $linkIds[$linkId] = true;
            }

            $this->collectLinkIds($this->itemsOf($item), $linkIds, $visited);
        }
    }

    /**
     * The `question` of each of an item's `enableWhen` conditions.
     *
     * @return list<string>
     */
    private function enableWhenQuestions(object $item): array
    {
        $conditions = $this->models->readInitializedValue($item, 'enableWhen');

        if (!is_array($conditions)) {
            return [];
        }

        $questions = [];

        foreach ($conditions as $condition) {
            if (is_object($condition)) {
                $question = $this->stringValue($this->models->readInitializedValue($condition, 'question'));

                if ($question !== null) {
                    $questions[] = $question;
                }
            }
        }

        return $questions;
    }

    /**
     * The `item` list, keyed as the model holds it so a finding's index names the actual occurrence.
     *
     * @return array<int|string, object>
     */
    private function itemsOf(object $node): array
    {
        $items = $this->models->readInitializedValue($node, 'item');

        if (!is_array($items)) {
            return [];
        }

        return array_filter($items, is_object(...));
    }

    /**
     * A `string` element arrives either as a bare string or as a primitive wrapper with a `value`.
     */
    private function stringValue(mixed $value): ?string
    {
        if (is_object($value)) {
            $value = $this->models->readInitializedValue($value, 'value');
        }

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * Read through the hierarchy so a Questionnaire profile class counts too.
     */
    private function isQuestionnaire(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isQuestionnaire[$key])) {
            return $this->isQuestionnaire[$key];
        }

        $found = false;

        foreach ($this->attributes->classAttributesInHierarchy($node, FhirResource::class) as $resource) {
            $found = $found || $resource->type === 'Questionnaire';
        }

        return $this->isQuestionnaire[$key] = $found;
    }
}
