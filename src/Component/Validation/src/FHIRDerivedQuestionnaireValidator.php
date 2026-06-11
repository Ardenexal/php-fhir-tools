<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItemAnswerOption;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;

/**
 * Validates a derived Questionnaire against its base Questionnaire.
 *
 * Rules are driven by the derivation type read from
 * `_derivedFrom[0].extension[questionnaire-derivationType]`:
 *   - compliesWith (default): new linkIds are forbidden; type/required/repeats/answerOption rules apply
 *   - extends: new linkIds are allowed; all other rules apply
 *   - inspiredBy: no structural constraints checked
 *
 * The derivationType cannot be read from the PHP model because the deserializer does not
 * merge FHIR primitive extension arrays (_derivedFrom) into CanonicalPrimitive->extension.
 * Callers must extract it from the raw JSON via extractDerivationTypeFromJson() and pass it
 * explicitly to validate(). The default is 'compliesWith' (conservative).
 */
final class FHIRDerivedQuestionnaireValidator
{
    private const DERIVATION_EXT_URL = 'http://hl7.org/fhir/StructureDefinition/questionnaire-derivationType';

    private const EXT_MIN_OCCURS     = 'http://hl7.org/fhir/StructureDefinition/questionnaire-minOccurs';

    private const EXT_MAX_OCCURS     = 'http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs';

    /**
     * Extract the derivation type code from a decoded JSON array of a Questionnaire.
     *
     * @param array<string, mixed> $decoded
     */
    public static function extractDerivationTypeFromJson(array $decoded): string
    {
        /** @var list<array<string, mixed>> $exts */
        $exts = $decoded['_derivedFrom'][0]['extension'] ?? [];

        foreach ($exts as $ext) {
            if (($ext['url'] ?? '') !== self::DERIVATION_EXT_URL) {
                continue;
            }

            $code = $ext['valueCoding']['code'] ?? null;
            if ($code !== null) {
                return (string) $code;
            }
        }

        return 'compliesWith';
    }

    /**
     * Validate a derived Questionnaire against its base.
     *
     * @param string $derivationType 'compliesWith' | 'extends' | 'inspiredBy' — caller must
     *                               extract this from raw JSON via extractDerivationTypeFromJson()
     *                               because the PHP model does not carry _derivedFrom extensions
     */
    public function validate(
        QuestionnaireResource $derived,
        QuestionnaireResource $base,
        string $derivationType = 'compliesWith',
    ): FHIRValidationReport {
        // newInstanceWithoutConstructor() bypasses constructor defaults; ?? guards uninitialized promoted arrays at runtime
        /** @phpstan-ignore nullCoalesce.property */
        if ($derivationType === 'inspiredBy' || ($derived->derivedFrom ?? []) === []) {
            return new FHIRValidationReport([]);
        }

        /** @phpstan-ignore nullCoalesce.property */
        $baseIndex  = $this->buildLevelIndex($base->item ?? []);
        /** @phpstan-ignore nullCoalesce.property */
        $violations = $this->compareItems($derived->item ?? [], $baseIndex, $derivationType, 'Questionnaire');

        return new FHIRValidationReport($violations);
    }

    /**
     * Build a linkId → item map for a single level of the item tree (non-recursive).
     *
     * @param array<QuestionnaireItem> $items
     *
     * @return array<string, QuestionnaireItem>
     */
    private function buildLevelIndex(array $items): array
    {
        $index = [];

        foreach ($items as $item) {
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($linkId !== '') {
                $index[$linkId] = $item;
            }
        }

        return $index;
    }

    /**
     * Compare derived items against a level-scoped base index, collecting violations.
     *
     * @param array<QuestionnaireItem>         $derivedItems
     * @param array<string, QuestionnaireItem> $baseIndex    linkId map for THIS level only
     *
     * @return list<FHIRValidationViolation>
     */
    private function compareItems(
        array $derivedItems,
        array $baseIndex,
        string $derivationType,
        string $basePath,
    ): array {
        $violations = [];

        foreach ($derivedItems as $i => $item) {
            $linkId = $item->linkId !== null ? (string) $item->linkId : '';

            if ($linkId === '') {
                continue;
            }

            $path = $basePath . '.item[' . $i . ']';

            if (!isset($baseIndex[$linkId])) {
                if ($derivationType === 'compliesWith') {
                    $violations[] = $this->violation(
                        $path,
                        "No item with linkId '{$linkId}' found in base Questionnaire",
                    );
                }

                continue;
            }

            $baseItem = $baseIndex[$linkId];

            $violations = array_merge($violations, $this->checkItemRules($item, $baseItem, $linkId, $path));

            // Recurse into children using only the base item's immediate children as the sub-index.
            $childIndex = $this->buildLevelIndex($baseItem->item);
            $violations = array_merge(
                $violations,
                $this->compareItems($item->item, $childIndex, $derivationType, $path),
            );
        }

        return $violations;
    }

    /**
     * Apply the per-item structural rules (type, required, repeats, answerOption).
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkItemRules(
        QuestionnaireItem $item,
        QuestionnaireItem $baseItem,
        string $linkId,
        string $path,
    ): array {
        $violations = [];

        // Rule: type cannot change.
        if ($item->type !== null && $baseItem->type !== null && (string) $item->type !== (string) $baseItem->type) {
            $violations[] = $this->violation(
                $path . '.type',
                "Item '{$linkId}' has type '{$baseItem->type}', cannot change to '{$item->type}'",
            );
        }

        // Rule: required cannot be weakened (base=true → derived=false is forbidden).
        // Scope: only when both sides are explicit booleans. A derived item that omits `required`
        // (null) is treated as "not explicitly constrained", not as the FHIR implicit default
        // (false). Weakening-via-omission is a known gap deferred beyond M08.
        if ($baseItem->required === true && $item->required === false) {
            $violations[] = $this->violation(
                $path . '.required',
                "Item '{$linkId}' is required in base, cannot be made optional",
            );
        }

        // Rule: repeats cannot be loosened (base=false → derived=true is forbidden).
        // Same null-omission scope as required above.
        if ($baseItem->repeats === false && $item->repeats === true) {
            $violations[] = $this->violation(
                $path . '.repeats',
                "Item '{$linkId}' does not repeat in base, cannot be set to repeat",
            );
        }

        // Rule: every derived answerOption (system+code) must appear in the base.
        foreach ($item->answerOption as $option) {
            if (!$option->value instanceof Coding) {
                continue;
            }

            $code   = (string) ($option->value->code ?? '');
            $system = (string) ($option->value->system ?? '');

            if (!$this->baseHasOption($baseItem->answerOption, $code, $system)) {
                $violations[] = $this->violation(
                    $path . '.answerOption',
                    "answerOption '{$code}' not present in base item '{$linkId}'",
                );
            }
        }

        // Rule: minOccurs cannot be weakened; maxOccurs cannot be widened.
        $derivedBounds = $this->readItemOccurrenceBounds($item);
        $baseBounds    = $this->readItemOccurrenceBounds($baseItem);

        if ($baseBounds['minOccurs'] !== null && $derivedBounds['minOccurs'] !== null
                                              && $derivedBounds['minOccurs'] < $baseBounds['minOccurs']) {
            $violations[] = $this->violation(
                $path . '.extension[questionnaire-minOccurs]',
                "Item '{$linkId}' minOccurs {$derivedBounds['minOccurs']} is weaker than base {$baseBounds['minOccurs']}",
            );
        }

        if ($baseBounds['maxOccurs'] !== null && $derivedBounds['maxOccurs'] !== null
                                              && $derivedBounds['maxOccurs'] > $baseBounds['maxOccurs']) {
            $violations[] = $this->violation(
                $path . '.extension[questionnaire-maxOccurs]',
                "Item '{$linkId}' maxOccurs {$derivedBounds['maxOccurs']} is weaker than base {$baseBounds['maxOccurs']}",
            );
        }

        return $violations;
    }

    /**
     * @param array<QuestionnaireItemAnswerOption> $baseOptions
     */
    private function baseHasOption(array $baseOptions, string $code, string $system): bool
    {
        foreach ($baseOptions as $base) {
            if (!$base->value instanceof Coding) {
                continue;
            }

            if ((string) ($base->value->code ?? '') === $code && (string) ($base->value->system ?? '') === $system) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{minOccurs: int|null, maxOccurs: int|null}
     */
    private function readItemOccurrenceBounds(QuestionnaireItem $item): array
    {
        $min = null;
        $max = null;

        foreach ($item->extension as $extension) {
            $url   = $extension->url;
            $value = $extension->value ?? null;

            if ($url === null || $value === null) {
                continue;
            }

            $int = $this->toInt($value);

            if ($int === null) {
                continue;
            }

            if ($url === self::EXT_MIN_OCCURS) {
                $min = $int;
            } elseif ($url === self::EXT_MAX_OCCURS) {
                $max = $int;
            }
        }

        return ['minOccurs' => $min, 'maxOccurs' => $max];
    }

    private function toInt(mixed $value): ?int
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

    private function violation(string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: FHIRDerivedQuestionnaireConstraint::class,
            profileGroup: null,
            invariantKey: null,
        );
    }
}
