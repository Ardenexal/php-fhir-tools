<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture\CountConstraintFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture\ProfileConstraintFixture;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FHIRValidationServiceIntegrationTest extends TestCase
{
    private FHIRValidationService $service;

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

        $validator     = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();
        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    public function testBuiltInCountViolationLandsInErrorBucket(): void
    {
        $fixture = new CountConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture);

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertSame('identifier', $report->errors()[0]->path);
        self::assertCount(0, $report->warnings());
    }

    public function testBuiltInCountPassesWhenPropertyPopulated(): void
    {
        $fixture = new CountConstraintFixture(identifier: ['http://example.org']);

        $report = $this->service->validate($fixture);

        self::assertTrue($report->isValid());
        self::assertCount(0, $report->errors());
    }

    public function testProfileConstraintViolationNotRaisedWithoutProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture); // no profile URL

        self::assertTrue($report->isValid(), 'Profile constraints must not fire without the profile group');
    }

    public function testProfileConstraintViolationRaisedWithProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertSame('identifier', $report->errors()[0]->path);
    }

    public function testProfileConstraintViolationCarriesProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertSame(ProfileConstraintFixture::PROFILE_URL, $report->errors()[0]->profileGroup);
    }

    public function testProfileConstraintPassesWhenConstraintSatisfied(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: ['http://example.org']);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertTrue($report->isValid());
    }
}
