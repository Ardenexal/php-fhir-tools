<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Validation;

/** One identifier: the slice discriminates on `system` and requires a `value` beneath it. */
final class SliceRulesIdentifierStub
{
    public function __construct(
        public ?string $system = null,
        public ?string $value = null,
    ) {
    }
}

#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
    rules: [['path' => 'value', 'constraint' => Count::class, 'options' => ['min' => 1]]],
)]
final class SliceRulesPatientStub
{
    /** @param list<SliceRulesIdentifierStub> $identifier */
    public function __construct(public array $identifier = [])
    {
    }
}

/** Closed slicing whose default slice requires a `value` on every item no named slice matched. */
#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: 'ihiNumber',
    discriminatorType: 'value',
    discriminatorPath: 'system',
    discriminatorValue: 'http://ihi.example.org',
)]
#[FHIRSliceConstraint(
    property: 'identifier',
    sliceName: '@default',
    discriminatorType: 'value',
    discriminatorPath: 'system',
    isDefault: true,
    rules: [['path' => 'value', 'constraint' => Count::class, 'options' => ['min' => 1]]],
)]
#[FHIRSlicingRules(property: 'identifier', rules: 'closed')]
final class SliceRulesDefaultSlicePatientStub
{
    /** @param list<SliceRulesIdentifierStub> $identifier */
    public function __construct(public array $identifier = [])
    {
    }
}

/** One coding of a component's code. */
final class SliceRulesCodingStub
{
    public function __construct(
        public ?string $system = null,
        public ?string $code = null,
    ) {
    }
}

/** A component's code, holding its codings. */
final class SliceRulesConceptStub
{
    /** @param list<SliceRulesCodingStub> $coding */
    public function __construct(public array $coding = [])
    {
    }
}

/** A component: its code decides the component slice, and its codings are sliced again beneath it. */
final class SliceRulesComponentStub
{
    public function __construct(public ?SliceRulesConceptStub $code = null)
    {
    }
}

/** The systolic component slice requires a SNOMED coding beneath it, through a nested slice. */
#[FHIRSliceConstraint(
    property: 'component',
    sliceName: 'SystolicBP',
    discriminatorType: 'value',
    discriminatorPath: 'code.coding.code',
    discriminatorValue: '8480-6',
    rules: [[
        'path'       => 'code.coding',
        'constraint' => FHIRSliceConstraint::class,
        'options'    => [
            'property'           => 'code.coding',
            'sliceName'          => 'snomedSBP',
            'min'                => 1,
            'max'                => 1,
            'discriminatorType'  => 'value',
            'discriminatorPath'  => 'code',
            'discriminatorValue' => '271649006',
            'rules'              => [['path' => 'system', 'constraint' => Count::class, 'options' => ['min' => 1]]],
        ],
    ]],
)]
final class SliceRulesObservationStub
{
    /** @param list<SliceRulesComponentStub> $component */
    public function __construct(public array $component = [])
    {
    }
}

/**
 * Rules carried on a FHIRSliceConstraint bind only the items that match that slice, and are
 * reported at the item's index.
 */
final class FHIRSliceConstraintRulesTest extends TestCase
{
    public function testRulesBindOnlyItemsMatchingTheSlice(): void
    {
        $patient = new SliceRulesPatientStub([
            new SliceRulesIdentifierStub('http://other.example.org'),
            new SliceRulesIdentifierStub('http://ihi.example.org', '8003608166690503'),
            new SliceRulesIdentifierStub('http://ihi.example.org'),
        ]);

        self::assertSame(
            ['identifier[2].value: This collection should contain 1 element or more.|This collection should contain 1 elements or more.'],
            $this->validate($patient),
        );
    }

    /** The default slice's rules bind the items no named slice matched, and only those. */
    public function testDefaultSliceRulesBindUnmatchedItems(): void
    {
        $patient = new SliceRulesDefaultSlicePatientStub([
            new SliceRulesIdentifierStub('http://ihi.example.org'),
            new SliceRulesIdentifierStub('http://other.example.org', 'A1'),
            new SliceRulesIdentifierStub('http://other.example.org'),
        ]);

        self::assertSame(
            ['identifier[2].value: This collection should contain 1 element or more.|This collection should contain 1 elements or more.'],
            $this->validate($patient),
        );
    }

    /**
     * A nested slice binds the codings of each component matching the outer slice: the diastolic
     * component needs no SNOMED coding, and a nested slice's own rules reach its matched coding.
     */
    public function testNestedSliceAppliesWithinEachMatchingItem(): void
    {
        $component = static fn (SliceRulesCodingStub ...$coding): SliceRulesComponentStub => new SliceRulesComponentStub(new SliceRulesConceptStub(array_values($coding)));

        $valid = new SliceRulesObservationStub([
            $component(new SliceRulesCodingStub('http://loinc.org', '8480-6'), new SliceRulesCodingStub('http://snomed.info/sct', '271649006')),
            $component(new SliceRulesCodingStub('http://loinc.org', '8462-4')),
        ]);
        self::assertSame([], $this->validate($valid));

        $invalid = new SliceRulesObservationStub([
            $component(new SliceRulesCodingStub('http://loinc.org', '8480-6')),
            $component(new SliceRulesCodingStub('http://loinc.org', '8480-6'), new SliceRulesCodingStub(null, '271649006')),
        ]);
        self::assertSame([
            'component[0].code.coding: Slice "snomedSBP" on "component[0].code.coding" requires at least 1 item(s), but 0 matched.',
            'component[1].code.coding[1].system: This collection should contain 1 element or more.|This collection should contain 1 elements or more.',
        ], $this->validate($invalid));
    }

    /**
     * @return list<string> "path: message" per violation
     */
    private function validate(object $subject): array
    {
        $factory = new class () implements ConstraintValidatorFactoryInterface {
            private ConstraintValidatorFactory $default;

            public function __construct()
            {
                $this->default = new ConstraintValidatorFactory();
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                if ($constraint instanceof FHIRSliceConstraint) {
                    $accessor = PropertyAccess::createPropertyAccessor();

                    return new FHIRSliceConstraintValidator($accessor, new SliceDiscriminatorMatcher($accessor));
                }

                return $this->default->getInstance($constraint);
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        $messages = [];
        foreach ($validator->validate($subject) as $violation) {
            $messages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        return $messages;
    }
}
