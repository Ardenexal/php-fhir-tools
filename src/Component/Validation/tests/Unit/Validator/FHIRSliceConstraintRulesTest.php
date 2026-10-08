<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
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
