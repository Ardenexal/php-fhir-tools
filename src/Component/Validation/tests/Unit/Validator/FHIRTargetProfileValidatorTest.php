<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

// ---------------------------------------------------------------------------
// File-level fixture classes — PHP 8.x requires compile-time constant expressions
// in attributes, so profile URLs must be string literals here, not self::CONST.
// ---------------------------------------------------------------------------

#[FHIRProfile(
    profileUrl: 'http://example.org/fhir/StructureDefinition/profile-a',
    baseType: 'Patient',
    fhirVersion: 'R4',
)]
final class ProfileATargetProfileFixture
{
}

#[FHIRProfile(
    profileUrl: 'http://example.org/fhir/StructureDefinition/profile-b',
    baseType: 'Organization',
    fhirVersion: 'R4',
)]
final class ProfileBTargetProfileFixture
{
}

/**
 * @extends ConstraintValidatorTestCase<FHIRTargetProfileValidator>
 */
final class FHIRTargetProfileValidatorTest extends ConstraintValidatorTestCase
{
    private const string PROFILE_A = 'http://example.org/fhir/StructureDefinition/profile-a';

    private const string PROFILE_B = 'http://example.org/fhir/StructureDefinition/profile-b';

    /** @var MockObject&FHIRReferenceResolverInterface */
    private MockObject $mockResolver;

    protected function setUp(): void
    {
        $this->mockResolver = $this->createMock(FHIRReferenceResolverInterface::class);
        parent::setUp();
    }

    protected function createValidator(): FHIRTargetProfileValidator
    {
        return new FHIRTargetProfileValidator(new NullFHIRReferenceResolver(), new FHIRValidationMessageRegistry());
    }

    // -------------------------------------------------------------------------
    // Skip cases
    // -------------------------------------------------------------------------

    public function testNullValueProducesNoViolation(): void
    {
        $this->validator->validate(null, new FHIRTargetProfile([self::PROFILE_A]));

        $this->assertNoViolation();
    }

    public function testStringValueProducesNoViolation(): void
    {
        $this->validator->validate('http://example.org/fhir/ValueSet/something', new FHIRTargetProfile([self::PROFILE_A]));

        $this->assertNoViolation();
    }

    public function testStringableValueProducesNoViolation(): void
    {
        $stringable = new class () implements \Stringable {
            public function __toString(): string
            {
                return 'http://example.org/some-canonical';
            }
        };

        $this->validator->validate($stringable, new FHIRTargetProfile([self::PROFILE_A]));

        $this->assertNoViolation();
    }

    public function testNullResolverSkipsValidationOnObject(): void
    {
        $ref = new \stdClass();

        $validator = new FHIRTargetProfileValidator(new NullFHIRReferenceResolver(), new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A]));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Matching profile → no violation
    // -------------------------------------------------------------------------

    public function testObjectWithMatchingProfileProducesNoViolation(): void
    {
        $resolved = new ProfileATargetProfileFixture();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A, self::PROFILE_B]));

        $this->assertNoViolation();
    }

    public function testObjectMatchingSecondOfMultipleProfilesProducesNoViolation(): void
    {
        $resolved = new ProfileBTargetProfileFixture();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A, self::PROFILE_B]));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Non-matching profile → ERROR
    // -------------------------------------------------------------------------

    public function testObjectWithNonMatchingProfileEmitsErrorViolation(): void
    {
        $resolved = new ProfileBTargetProfileFixture();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A]));

        $this->buildViolation(FHIRTargetProfileValidator::DEFAULT_PROFILE_MISMATCH_MESSAGE)
            ->setParameters([
                '{{ profiles }}' => self::PROFILE_A,
                '{{ actual }}'   => self::PROFILE_B,
            ])
            ->setInvalidValue($resolved)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // -------------------------------------------------------------------------
    // No #[FHIRProfile] → WARNING
    // -------------------------------------------------------------------------

    public function testObjectWithNoProfileAttributeEmitsWarningViolation(): void
    {
        $resolved = new \stdClass();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A]));

        $this->buildViolation(FHIRTargetProfileValidator::DEFAULT_UNVERIFIABLE_MESSAGE)
            ->setParameters(['{{ profiles }}' => self::PROFILE_A])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // -------------------------------------------------------------------------
    // Array values
    // -------------------------------------------------------------------------

    public function testArrayWithMismatchedItemEmitsErrorViolation(): void
    {
        $resolved = new ProfileBTargetProfileFixture();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);
        $validator->validate([$ref], new FHIRTargetProfile([self::PROFILE_A]));

        $this->buildViolation(FHIRTargetProfileValidator::DEFAULT_PROFILE_MISMATCH_MESSAGE)
            ->setParameters([
                '{{ profiles }}' => self::PROFILE_A,
                '{{ actual }}'   => self::PROFILE_B,
            ])
            ->setInvalidValue($resolved)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testEmptyArrayProducesNoViolation(): void
    {
        $this->validator->validate([], new FHIRTargetProfile([self::PROFILE_A]));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Null resolver contract
    // -------------------------------------------------------------------------

    public function testNullReferenceResolverAlwaysReturnsNull(): void
    {
        $resolver = new NullFHIRReferenceResolver();

        self::assertNull($resolver->resolve(new \stdClass()));
        self::assertNull($resolver->resolve(new \stdClass()));
    }
}
