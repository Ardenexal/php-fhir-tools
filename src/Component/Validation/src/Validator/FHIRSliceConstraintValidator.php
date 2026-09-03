<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRElementPath;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
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
    private static ?\WeakMap $processedKeys = null;

    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly SliceDiscriminatorMatcher $matcher,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Static on purpose. A ConstraintValidatorFactory is free to return a fresh validator for every
     * constraint it is asked about, and the common ones do — so per-instance dedup state is always
     * empty and never dedups anything. `bp` declares two slices on `component`, which meant the
     * property was matched twice and every finding reported twice over. Keying on the
     * ExecutionContext keeps runs isolated, and the WeakMap lets each entry go when its context does.
     *
     * @return \WeakMap<ExecutionContextInterface, array<string, true>>
     */
    private static function processedKeys(): \WeakMap
    {
        /** @var \WeakMap<ExecutionContextInterface, array<string, true>> $map */
        $map = self::$processedKeys ??= new \WeakMap();

        return $map;
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
        $keys      = self::processedKeys();
        $processed = $keys[$ctx] ?? [];
        if (isset($processed[$dedupKey])) {
            return;
        }
        $processed[$dedupKey] = true;
        $keys[$ctx]           = $processed;

        // Read the property value (must be an array for sliced properties)
        $readPath = FHIRElementPath::toPropertyPath($property);

        if (!$this->propertyAccessor->isReadable($value, $readPath)) {
            return;
        }

        $items = $this->propertyAccessor->getValue($value, $readPath);
        if (!is_array($items)) {
            return;
        }

        // Collect all slice constraints for this property + active group
        $sliceConstraints = $this->collectSliceConstraints($value, $property, $activeGroup);
        if ($sliceConstraints === []) {
            return;
        }

        // Read slicing rules for this property (if any)
        $slicingRules = $this->readSlicingRules($value, $property, $activeGroup);

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
     * @return list<FHIRSliceConstraint>
     */
    private function collectSliceConstraints(
        object $subject,
        string $property,
        string $activeGroup,
    ): array {
        $results = [];
        // Ancestors included: a derived profile inherits its parent's slices without re-declaring
        // them. ObservationBpProfile extends ObservationVitalsignsProfile and the VSCat slice on
        // category lives on the parent, so reading the instance class alone finds nothing and the
        // required slice is silently never checked.
        foreach ($this->attributes->classAttributesInHierarchy($subject, FHIRSliceConstraint::class) as $sc) {
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

    private function readSlicingRules(
        object $subject,
        string $property,
        string $activeGroup,
    ): ?FHIRSlicingRules {
        // Ancestors too, for the same reason as collectSliceConstraints(): an inherited slice brings
        // its inherited slicing rules with it, and losing them turns closed slicing into open.
        foreach ($this->attributes->classAttributesInHierarchy($subject, FHIRSlicingRules::class) as $rules) {
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
