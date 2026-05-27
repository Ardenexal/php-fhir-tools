<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Integration spike: confirms FHIRProfileConstraintValidator produces violations only when
 * the profile URL group is active, not under Default-only validation.
 *
 * Uses the real Symfony Validator with attribute mapping to confirm:
 *  - Group activation (Default-only → no profile violations)
 *  - Violation path propagation (violation appears at property path)
 *  - Multiple constraints on different paths
 *  - Named-arg unpack for inner constraint options
 */
final class FHIRProfileConstraintValidatorTest extends TestCase
{
    public const string PROFILE_URL = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $defaultFactory   = new ConstraintValidatorFactory();

        $factory = new class ($propertyAccessor, $defaultFactory) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly ConstraintValidatorFactory $default,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                if ($constraint instanceof FHIRProfileConstraint) {
                    return new FHIRProfileConstraintValidator($this->accessor);
                }

                return $this->default->getInstance($constraint);
            }
        };

        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();
    }

    public function testNoViolationWhenProfileGroupNotActive(): void
    {
        $subject = new FakePatientProfileSubject(name: [], identifier: []);

        $violations = $this->validator->validate($subject, groups: ['Default']);

        self::assertCount(0, $violations, 'Default-only validation must not trigger profile constraints');
    }

    public function testViolationRaisedOnNamePathWhenGroupActiveAndConstraintFails(): void
    {
        $subject = new FakePatientProfileSubject(name: [], identifier: [['system' => 'x']]);

        $violations = $this->validator->validate($subject, groups: ['Default', self::PROFILE_URL]);

        $paths = array_map(
            static fn (ConstraintViolationInterface $v) => $v->getPropertyPath(),
            iterator_to_array($violations),
        );

        self::assertContains('name', $paths, 'Expected a violation on the "name" path');
    }

    public function testViolationRaisedOnIdentifierPathWhenGroupActiveAndConstraintFails(): void
    {
        $subject = new FakePatientProfileSubject(name: [['text' => 'Smith']], identifier: []);

        $violations = $this->validator->validate($subject, groups: ['Default', self::PROFILE_URL]);

        self::assertCount(1, $violations);
        self::assertSame('identifier', $violations[0]->getPropertyPath());
    }

    public function testNoViolationWhenAllConstraintsSatisfied(): void
    {
        $subject = new FakePatientProfileSubject(
            name: [['text' => 'Smith']],
            identifier: [['system' => 'https://example.org']],
        );

        $violations = $this->validator->validate($subject, groups: ['Default', self::PROFILE_URL]);

        self::assertCount(0, $violations, 'No violations expected when all profile constraints are satisfied');
    }

    public function testBothViolationsRaisedWhenBothConstraintsFail(): void
    {
        $subject = new FakePatientProfileSubject(name: [], identifier: []);

        $violations = $this->validator->validate($subject, groups: ['Default', self::PROFILE_URL]);

        self::assertCount(2, $violations);

        $paths = array_map(
            static fn (ConstraintViolationInterface $v) => $v->getPropertyPath(),
            iterator_to_array($violations),
        );

        self::assertContains('name', $paths);
        self::assertContains('identifier', $paths);
    }

    public function testArrayIndexedInnerViolationPathHasNoDotBeforeBracket(): void
    {
        $profileConstraint = new FHIRProfileConstraint(
            path: 'items',
            constraint: All::class,
            options: ['constraints' => [new NotBlank()]],
            groups: [self::PROFILE_URL],
        );

        $subject = new FakeItemsSubject(items: [null]);

        $violations = $this->validator->validate($subject, [$profileConstraint], ['Default', self::PROFILE_URL]);

        self::assertCount(1, $violations);
        self::assertSame('items[0]', $violations[0]->getPropertyPath());
    }
}

final class FakeItemsSubject
{
    /** @param list<mixed> $items */
    public function __construct(
        public readonly array $items,
    ) {
    }
}

/**
 * Fake profile class used as the validation target in the spike tests.
 * Mirrors the pattern the generator will emit: repeatable #[FHIRProfileConstraint] at class level.
 */
#[FHIRProfileConstraint(
    path: 'name',
    constraint: Count::class,
    options: ['min' => 1],
    groups: [FHIRProfileConstraintValidatorTest::PROFILE_URL],
)]
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: Count::class,
    options: ['min' => 1],
    groups: [FHIRProfileConstraintValidatorTest::PROFILE_URL],
)]
final class FakePatientProfileSubject
{
    /**
     * @param list<mixed> $name
     * @param list<mixed> $identifier
     */
    public function __construct(
        public readonly array $name,
        public readonly array $identifier,
    ) {
    }
}
