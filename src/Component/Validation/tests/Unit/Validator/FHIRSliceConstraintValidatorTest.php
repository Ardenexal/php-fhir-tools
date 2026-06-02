<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

// ---------------------------------------------------------------------------
// Fixture classes – defined at file scope so PHP attributes are readable via
// ReflectionClass inside FHIRSliceConstraintValidator.
// Groups are intentionally empty so the 'Default' group (used by the test
// context) activates these constraints without profile-URL activation.
// ---------------------------------------------------------------------------

#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
    groups: [],
)]
#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'medicareNumber',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://medicare.example.org',
    groups: [],
)]
#[FHIRSlicingRules(property: 'identifier', rules: 'open')]
class SliceTestPatientOpen
{
    /** @var object[] */
    public array $identifier = [];
}

#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
    groups: [],
)]
#[FHIRSlicingRules(property: 'identifier', rules: 'closed')]
class SliceTestPatientClosed
{
    /** @var object[] */
    public array $identifier = [];
}

#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
    groups: [],
)]
#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: '@default',
    min: 0,
    max: 2,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: null,
    isDefault: true,
    groups: [],
)]
#[FHIRSlicingRules(property: 'identifier', rules: 'closed')]
class SliceTestPatientClosedWithDefault
{
    /** @var object[] */
    public array $identifier = [];
}

#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
    groups: [],
)]
#[FHIRSlicingRules(property: 'identifier', rules: 'openAtEnd')]
class SliceTestPatientOpenAtEnd
{
    /** @var object[] */
    public array $identifier = [];
}

// ---------------------------------------------------------------------------

/**
 * @extends ConstraintValidatorTestCase<FHIRSliceConstraintValidator>
 */
final class FHIRSliceConstraintValidatorTest extends ConstraintValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset property path so atPath('identifier') resolves to 'identifier', not 'property.path.identifier'
        $this->setPropertyPath('');
    }

    protected function createValidator(): FHIRSliceConstraintValidator
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return new FHIRSliceConstraintValidator($accessor, new SliceDiscriminatorMatcher($accessor));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function makeIdentifier(string $system): object
    {
        return new class ($system) {
            public function __construct(public readonly string $system)
            {
            }
        };
    }

    private function firstConstraintOf(object $obj): FHIRSliceConstraint
    {
        $attrs = (new \ReflectionClass($obj))->getAttributes(FHIRSliceConstraint::class);

        return $attrs[0]->newInstance();
    }

    // ------------------------------------------------------------------
    // Null / non-object passthrough
    // ------------------------------------------------------------------

    public function testNullValueProducesNoViolation(): void
    {
        $this->validator->validate(null, $this->firstConstraintOf(new SliceTestPatientOpen()));

        $this->assertNoViolation();
    }

    // ------------------------------------------------------------------
    // Min satisfied
    // ------------------------------------------------------------------

    public function testMinSatisfiedProducesNoViolation(): void
    {
        $obj               = new SliceTestPatientOpen();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->assertNoViolation();
    }

    // ------------------------------------------------------------------
    // Min violated
    // ------------------------------------------------------------------

    public function testMinViolatedEmitsViolation(): void
    {
        $obj = new SliceTestPatientOpen();
        // empty – ihiNumber min=1, nothing matches

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->buildViolation(
            'Slice "{{ slice }}" on "{{ property }}" requires at least {{ min }} item(s), but {{ count }} matched.',
        )
            ->setParameters([
                '{{ slice }}'    => 'ihiNumber',
                '{{ property }}' => 'identifier',
                '{{ min }}'      => '1',
                '{{ count }}'    => '0',
            ])
            ->atPath('identifier')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // ------------------------------------------------------------------
    // Max exceeded
    // ------------------------------------------------------------------

    public function testMaxExceededEmitsViolation(): void
    {
        $obj               = new SliceTestPatientOpen();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org'); // max=1, count=2

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->buildViolation(
            'Slice "{{ slice }}" on "{{ property }}" allows at most {{ max }} item(s), but {{ count }} matched.',
        )
            ->setParameters([
                '{{ slice }}'    => 'ihiNumber',
                '{{ property }}' => 'identifier',
                '{{ max }}'      => '1',
                '{{ count }}'    => '2',
            ])
            ->atPath('identifier')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // ------------------------------------------------------------------
    // Open slicing – unmatched items allowed
    // ------------------------------------------------------------------

    public function testOpenSlicingAllowsUnmatchedItems(): void
    {
        $obj               = new SliceTestPatientOpen();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');        // matches ihiNumber
        $obj->identifier[] = $this->makeIdentifier('http://unknown-system.example.org'); // no match → allowed

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->assertNoViolation();
    }

    // ------------------------------------------------------------------
    // Closed slicing – unmatched items rejected
    // ------------------------------------------------------------------

    public function testClosedSlicingRejectsUnmatchedItems(): void
    {
        $obj               = new SliceTestPatientClosed();
        $obj->identifier[] = $this->makeIdentifier('http://unknown-system.example.org');

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->buildViolation(
            'Property "{{ property }}" uses closed slicing but contains {{ count }} item(s) matching no defined slice.',
        )
            ->setParameters([
                '{{ property }}' => 'identifier',
                '{{ count }}'    => '1',
            ])
            ->atPath('identifier')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testClosedSlicingWithMatchedItemProducesNoViolation(): void
    {
        $obj               = new SliceTestPatientClosed();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->assertNoViolation();
    }

    // ------------------------------------------------------------------
    // Closed slicing with @default slice
    // ------------------------------------------------------------------

    public function testClosedSlicingDefaultSliceAbsorbsUnmatchedItems(): void
    {
        $obj               = new SliceTestPatientClosedWithDefault();
        $obj->identifier[] = $this->makeIdentifier('http://unknown.example.org'); // goes to @default

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        // @default allows 0–2 unmatched; 1 unmatched is within range → no violation
        $this->assertNoViolation();
    }

    public function testClosedSlicingDefaultSliceMaxExceededEmitsViolation(): void
    {
        $obj = new SliceTestPatientClosedWithDefault();
        for ($i = 0; $i < 3; ++$i) {
            $obj->identifier[] = $this->makeIdentifier('http://unknown.example.org');
        }

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->buildViolation(
            'Default slice on "{{ property }}" allows at most {{ max }} unmatched item(s), but {{ count }} found.',
        )
            ->setParameters([
                '{{ property }}' => 'identifier',
                '{{ max }}'      => '2',
                '{{ count }}'    => '3',
            ])
            ->atPath('identifier')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // ------------------------------------------------------------------
    // openAtEnd slicing
    // ------------------------------------------------------------------

    public function testOpenAtEndSlicingViolationWhenMatchedItemFollowsUnmatched(): void
    {
        $obj               = new SliceTestPatientOpenAtEnd();
        $obj->identifier[] = $this->makeIdentifier('http://unknown.example.org'); // unmatched first
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');     // matched after unmatched → violation

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->buildViolation(
            'Property "{{ property }}" uses openAtEnd slicing: unmatched items must appear after all matched slice items.',
        )
            ->setParameters(['{{ property }}' => 'identifier'])
            ->atPath('identifier')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testOpenAtEndSlicingAllowsUnmatchedAfterMatched(): void
    {
        $obj               = new SliceTestPatientOpenAtEnd();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');     // matched first
        $obj->identifier[] = $this->makeIdentifier('http://unknown.example.org'); // unmatched after → allowed

        $this->validator->validate($obj, $this->firstConstraintOf($obj));

        $this->assertNoViolation();
    }

    // ------------------------------------------------------------------
    // Deduplication: second validate() call on same object is no-op
    // ------------------------------------------------------------------

    public function testSecondValidateCallForSamePropertyIsNoop(): void
    {
        $obj               = new SliceTestPatientOpen();
        $obj->identifier[] = $this->makeIdentifier('http://ihi.example.org');

        // First call (satisfies min=1 for ihiNumber, medicareNumber is optional)
        $this->validator->validate($obj, $this->firstConstraintOf($obj));
        $this->assertNoViolation();

        // Second call with second attribute – dedup prevents re-processing
        $secondConstraint = (new \ReflectionClass($obj))->getAttributes(FHIRSliceConstraint::class)[1]->newInstance();
        $this->validator->validate($obj, $secondConstraint);
        $this->assertNoViolation();
    }

    public function testRevalidatingSameObjectWithFreshContextProducesViolations(): void
    {
        // SliceTestPatientOpen requires ihiNumber (min:1). An empty identifiers array violates this.
        $obj = new SliceTestPatientOpen();

        $constraint = $this->firstConstraintOf($obj);

        // First call — records dedup key in the current context
        $this->validator->validate($obj, $constraint);
        $firstCount = count($this->context->getViolations());
        self::assertGreaterThan(0, $firstCount, 'First validation must find the min:1 violation');

        // Simulate a new $validator->validate() call by re-initializing with a fresh context.
        // With the WeakMap keyed on ExecutionContext, the dedup state does not carry over.
        $freshContext = $this->createContext();
        $this->validator->initialize($freshContext);
        $this->validator->validate($obj, $constraint);
        self::assertGreaterThan(
            0,
            count($freshContext->getViolations()),
            'Re-validation with a fresh context must also find violations — WeakMap dedup must not persist across $validator->validate() calls',
        );
    }
}
