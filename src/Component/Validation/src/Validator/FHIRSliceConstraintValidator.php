<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Validates FHIR slice membership on sliced array properties.
 *
 * Symfony invokes this validator once per #[FHIRSliceConstraint] attribute on the class.
 * When first invoked for a (property, active-group) pair on an object, it performs a
 * complete validation pass: reads all slices for that property, counts item matches,
 * checks min/max cardinality, and (for closed slicing) rejects items matching no slice.
 *
 * Subsequent invocations for the same (property, active-group, object) within a single
 * $validator->validate() call are no-ops; a WeakMap keyed on the ExecutionContext prevents
 * duplicate violations when multiple slice attributes exist for one property.
 * Keying on the context (not the validated object) means re-validation of the same object
 * in a later call — with a fresh context — always runs a full pass.
 *
 * @author Ardenexal
 */
final class FHIRSliceConstraintValidator extends ConstraintValidator
{
    /**
     * Tracks already-validated (property, group) pairs per ExecutionContext.
     * The ExecutionContext is a new object per $validator->validate() call, so dedup
     * naturally resets between separate validation passes on the same object.
     *
     * @var \WeakMap<ExecutionContextInterface, array<string, true>>
     */
    private \WeakMap $processedKeys;

    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly SliceDiscriminatorMatcher $matcher,
    ) {
        /** @var \WeakMap<ExecutionContextInterface, array<string, true>> $processedKeys */
        $processedKeys       = new \WeakMap();
        $this->processedKeys = $processedKeys;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRSliceConstraint) {
            throw new UnexpectedTypeException($constraint, FHIRSliceConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $property    = $constraint->property;
        $activeGroup = $this->context->getGroup() ?? 'Default';
        $dedupKey    = "{$property}|{$activeGroup}";

        // Only process each (property, group) combination once per $validator->validate() call.
        // Keyed on the ExecutionContext so the dedup resets when a new call creates a fresh context.
        $ctx       = $this->context;
        $processed = $this->processedKeys[$ctx] ?? [];
        if (isset($processed[$dedupKey])) {
            return;
        }
        $processed[$dedupKey]      = true;
        $this->processedKeys[$ctx] = $processed;

        // Read the property value (must be an array for sliced properties)
        if (!$this->propertyAccessor->isReadable($value, $property)) {
            return;
        }

        $items = $this->propertyAccessor->getValue($value, $property);
        if (!is_array($items)) {
            return;
        }

        $refl = new \ReflectionClass($value);

        // Collect all slice constraints for this property + active group
        $sliceConstraints = $this->collectSliceConstraints($refl, $property, $activeGroup);
        if ($sliceConstraints === []) {
            return;
        }

        // Read slicing rules for this property (if any)
        $slicingRules = $this->readSlicingRules($refl, $property, $activeGroup);

        // Match items → slices
        $this->validateSlices($items, $sliceConstraints, $slicingRules, $property);
    }

    /**
     * @param array<mixed>              $items
     * @param list<FHIRSliceConstraint> $sliceConstraints
     */
    private function validateSlices(
        array $items,
        array $sliceConstraints,
        ?FHIRSlicingRules $slicingRules,
        string $property,
    ): void {
        $rules         = $slicingRules !== null ? $slicingRules->rules : 'open';
        $defaultSlice  = null;
        $namedSlices   = [];

        foreach ($sliceConstraints as $sc) {
            if ($sc->isDefault) {
                $defaultSlice = $sc;
            } else {
                $namedSlices[] = $sc;
            }
        }

        // Track match counts per slice and which items matched any slice
        /** @var array<int, int> $matchCounts  sliceConstraints index → count */
        $matchCounts        = array_fill(0, count($namedSlices), 0);
        $unmatchedItems     = [];
        $openAtEndViolation = false;

        foreach ($items as $itemIndex => $item) {
            if (!is_object($item) && !is_array($item)) {
                continue;
            }

            $matched = false;
            foreach ($namedSlices as $sliceIdx => $sc) {
                if ($this->matcher->matches(
                    $item,
                    $sc->discriminatorType,
                    $sc->discriminatorPath,
                    $sc->discriminatorValue,
                )) {
                    ++$matchCounts[$sliceIdx];
                    $matched = true;

                    // openAtEnd: a matched item after any unmatched item violates ordering
                    if ($rules === 'openAtEnd' && $unmatchedItems !== []) {
                        $openAtEndViolation = true;
                    }
                    break;
                }
            }

            if (!$matched) {
                $unmatchedItems[] = $itemIndex;
            }
        }

        // Check min/max per named slice
        foreach ($namedSlices as $sliceIdx => $sc) {
            $count = $matchCounts[$sliceIdx];

            if ($count < $sc->min) {
                $this->context->buildViolation(
                    'Slice "{{ slice }}" on "{{ property }}" requires at least {{ min }} item(s), but {{ count }} matched.',
                )->setParameters([
                    '{{ slice }}'    => $sc->sliceName,
                    '{{ property }}' => $property,
                    '{{ min }}'      => (string) $sc->min,
                    '{{ count }}'    => (string) $count,
                ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
            }

            $maxInt = is_numeric($sc->max) ? (int) $sc->max : PHP_INT_MAX;
            if ($count > $maxInt) {
                $this->context->buildViolation(
                    'Slice "{{ slice }}" on "{{ property }}" allows at most {{ max }} item(s), but {{ count }} matched.',
                )->setParameters([
                    '{{ slice }}'    => $sc->sliceName,
                    '{{ property }}' => $property,
                    '{{ max }}'      => (string) $sc->max,
                    '{{ count }}'    => (string) $count,
                ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
            }
        }

        // Closed slicing: unmatched items must go to @default or be rejected
        if ($rules === 'closed' && $unmatchedItems !== []) {
            if ($defaultSlice !== null) {
                $unmatchedCount = count($unmatchedItems);
                $maxInt         = is_numeric($defaultSlice->max) ? (int) $defaultSlice->max : PHP_INT_MAX;

                if ($unmatchedCount < $defaultSlice->min) {
                    $this->context->buildViolation(
                        'Default slice on "{{ property }}" requires at least {{ min }} unmatched item(s), but {{ count }} found.',
                    )->setParameters([
                        '{{ property }}' => $property,
                        '{{ min }}'      => (string) $defaultSlice->min,
                        '{{ count }}'    => (string) $unmatchedCount,
                    ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
                }

                if ($unmatchedCount > $maxInt) {
                    $this->context->buildViolation(
                        'Default slice on "{{ property }}" allows at most {{ max }} unmatched item(s), but {{ count }} found.',
                    )->setParameters([
                        '{{ property }}' => $property,
                        '{{ max }}'      => (string) $defaultSlice->max,
                        '{{ count }}'    => (string) $unmatchedCount,
                    ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
                }
            } else {
                $this->context->buildViolation(
                    'Property "{{ property }}" uses closed slicing but contains {{ count }} item(s) matching no defined slice.',
                )->setParameters([
                    '{{ property }}' => $property,
                    '{{ count }}'    => (string) count($unmatchedItems),
                ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
            }
        }

        // openAtEnd: unmatched items must only appear after all matched items
        if ($openAtEndViolation) {
            $this->context->buildViolation(
                'Property "{{ property }}" uses openAtEnd slicing: unmatched items must appear after all matched slice items.',
            )->setParameters([
                '{{ property }}' => $property,
            ])->atPath($property)->setCode(FHIRViolationCode::ERROR)->addViolation();
        }
    }

    /**
     * @param \ReflectionClass<object> $refl
     *
     * @return list<FHIRSliceConstraint>
     */
    private function collectSliceConstraints(
        \ReflectionClass $refl,
        string $property,
        string $activeGroup,
    ): array {
        $results = [];
        foreach ($refl->getAttributes(FHIRSliceConstraint::class) as $attr) {
            /** @var FHIRSliceConstraint $sc */
            $sc = $attr->newInstance();
            if ($sc->property !== $property) {
                continue;
            }
            $groups = $sc->groups ?? [];
            if ($groups !== [] && !in_array($activeGroup, $groups, true)) {
                continue;
            }
            $results[] = $sc;
        }

        return $results;
    }

    /**
     * @param \ReflectionClass<object> $refl
     */
    private function readSlicingRules(
        \ReflectionClass $refl,
        string $property,
        string $activeGroup,
    ): ?FHIRSlicingRules {
        foreach ($refl->getAttributes(FHIRSlicingRules::class) as $attr) {
            /** @var FHIRSlicingRules $rules */
            $rules = $attr->newInstance();
            if ($rules->property !== $property) {
                continue;
            }
            if ($rules->groups !== [] && !in_array($activeGroup, $rules->groups, true)) {
                continue;
            }

            return $rules;
        }

        return null;
    }
}
