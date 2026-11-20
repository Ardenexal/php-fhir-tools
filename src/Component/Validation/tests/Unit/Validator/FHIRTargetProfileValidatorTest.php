<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Profile\TargetProfileBaseTypes;
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
    use BindsValidatorContext;

    private const string PROFILE_A = 'http://example.org/fhir/StructureDefinition/profile-a';

    private const string PROFILE_B = 'http://example.org/fhir/StructureDefinition/profile-b';

    private const string CORE_QUESTIONNAIRE = 'http://hl7.org/fhir/StructureDefinition/Questionnaire';

    private const string CORE_DEVICE = 'http://hl7.org/fhir/StructureDefinition/Device';

    /** A profile no source knows: no IG class, no generated core class, no registered definition. */
    private const string UNKNOWN_PROFILE = 'http://example.org/fhir/StructureDefinition/unknown';

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
        $this->bindContext($validator);
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
        $this->bindContext($validator);
        $validator->validate($ref, new FHIRTargetProfile([self::PROFILE_A, self::PROFILE_B]));

        $this->assertNoViolation();
    }

    public function testObjectMatchingSecondOfMultipleProfilesProducesNoViolation(): void
    {
        $resolved = new ProfileBTargetProfileFixture();
        $ref      = new \stdClass();

        $this->mockResolver->method('resolve')->willReturn($resolved);

        $validator = new FHIRTargetProfileValidator($this->mockResolver, new FHIRValidationMessageRegistry());
        $this->bindContext($validator);
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
        $this->bindContext($validator);
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
        $this->bindContext($validator);
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
        $this->bindContext($validator);
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

    // -------------------------------------------------------------------------
    // Canonical type (R4.qr-bad-ref2)
    // -------------------------------------------------------------------------

    public function testCanonicalNamingAnotherResourceTypeEmitsErrorViolation(): void
    {
        $canonical = 'http://hl7.org/fhir/ValueSet/example-extensional';

        $this->validator->validate(new CanonicalPrimitive(value: $canonical), new FHIRTargetProfile([self::CORE_QUESTIONNAIRE]));

        $this->buildViolation(FHIRTargetProfileValidator::CANONICAL_TYPE_MESSAGE)
            ->setParameters([
                '{{ canonical }}' => $canonical,
                '{{ type }}'      => 'ValueSet',
                '{{ allowed }}'   => 'Questionnaire',
            ])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A version and a fragment are not part of the path, and a canonical that is not of the
     * `[base]/Type/id` shape names no type at all.
     */
    public function testCanonicalOfAnAllowedTypeOrWithNoTypeInItsPathIsAccepted(): void
    {
        $constraint = new FHIRTargetProfile([self::CORE_QUESTIONNAIRE]);

        $this->validator->validate('http://example.org/fhir/Questionnaire/q1|1.0.0', $constraint);
        $this->validator->validate('http://example.org/fhir/Questionnaire/q1#frag', $constraint);
        $this->validator->validate('http://loinc.org/q/12345-6', $constraint);
        $this->validator->validate('urn:uuid:3fa85f64-5717-4562-b3fc-2c963f66afa6', $constraint);

        $this->assertNoViolation();
    }

    /**
     * A profile no source knows, or an abstract type, as the target says nothing a type name can be
     * compared to.
     */
    public function testCanonicalTypeIsNotCheckedAgainstNonCoreOrAbstractTargets(): void
    {
        $canonical = 'http://hl7.org/fhir/ValueSet/example-extensional';

        $this->validator->validate($canonical, new FHIRTargetProfile([self::PROFILE_A]));
        $this->validator->validate($canonical, new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Resource']));
        $this->validator->validate($canonical, new FHIRTargetProfile([self::CORE_QUESTIONNAIRE, self::UNKNOWN_PROFILE]));

        $this->assertNoViolation();
    }

    /**
     * `PlanDefinition.action.definition[x]` carries the canonical's target list on its `uri` variant
     * too, and a uri may point anywhere. `CanonicalPrimitive` extends `UriPrimitive`, so this pins
     * that the primitive type, not the class hierarchy, decides.
     */
    public function testUriVariantIsNotReadAsNamingAType(): void
    {
        $constraint = new FHIRTargetProfile([self::CORE_QUESTIONNAIRE]);

        $this->validator->validate(new UriPrimitive(value: 'http://example.org/fhir/Patient/example'), $constraint);
        $this->validator->validate([new UriPrimitive(value: 'http://example.org/fhir/Patient/example')], $constraint);

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Conditional references (R5.bundle-conditional-reference-bad)
    // -------------------------------------------------------------------------

    public function testConditionalReferenceWithMalformedQueryEmitsErrorViolation(): void
    {
        $this->validator->validate(new Reference(reference: 'Device?identifier=1234&?=='), new FHIRTargetProfile([self::CORE_DEVICE]));

        $this->buildViolation(FHIRTargetProfileValidator::CONDITIONAL_QUERY_MESSAGE)
            ->setParameter('{{ reference }}', 'Device?identifier=1234&?==')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testWellFormedConditionalReferencesAreAccepted(): void
    {
        $constraint = new FHIRTargetProfile([self::CORE_DEVICE]);

        $this->validator->validate(new Reference(reference: 'Device?identifier=http://acme.org|1234'), $constraint);
        $this->validator->validate(new Reference(reference: 'Device?identifier=1234&patient:Patient.name='), $constraint);
        $this->validator->validate(new Reference(reference: 'Device?_has:Observation:device:code=1'), $constraint);

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Reference type read from a literal reference (#229)
    // -------------------------------------------------------------------------

    /**
     * The HL7 reference validator reads a type from a reference URL only under its opt-in
     * `assumeValidRestReferences`; this validator always does, so `Practitioner/2` fails an
     * Organization-only element without being resolved.
     */
    public function testReferenceNamingAnotherResourceTypeEmitsErrorViolation(): void
    {
        $this->validator->validate(new Reference(reference: 'Practitioner/2'), new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Organization']));

        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'Practitioner', '{{ allowed }}' => 'Organization'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testAbsoluteAndVersionedReferencesAreReadForTheirType(): void
    {
        $constraint = new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/PractitionerRole']);

        $this->validator->validate(new Reference(reference: 'https://example.org/fhir/Patient/1/_history/2'), $constraint);

        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'Patient', '{{ allowed }}' => 'PractitionerRole'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * An allowed type, a reference that names no type (contained, `urn:uuid:`), a target that is not
     * a core resource type, and an abstract target all pass: none of them says the type is wrong.
     */
    public function testReferenceTypeIsOnlyComparedWhenBothSidesNameAType(): void
    {
        $core = new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Patient', 'http://hl7.org/fhir/StructureDefinition/Group']);

        $this->validator->validate(new Reference(reference: 'Group/1'), $core);
        $this->validator->validate(new Reference(reference: '#p1'), $core);
        $this->validator->validate(new Reference(reference: 'urn:uuid:9d7c2a3e-8a4f-4f43-9e3b-6b1c2d3e4f50'), $core);
        $this->validator->validate(new Reference(reference: 'Practitioner/2'), new FHIRTargetProfile([self::UNKNOWN_PROFILE]));
        $this->validator->validate(new Reference(reference: 'Practitioner/2'), new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Resource']));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Targets that are profiles, read for the type they constrain (#246)
    // -------------------------------------------------------------------------

    /**
     * `Reference(au-core-patient)` allows Patient: a generated IG class registered for the URL says so.
     */
    public function testIgProfileTargetIsReadForItsBaseType(): void
    {
        $validator  = $this->validatorWith(new TargetProfileBaseTypes(new FHIRIGTypeRegistry([], [self::PROFILE_A => ProfileATargetProfileFixture::class])));
        $constraint = new FHIRTargetProfile([self::PROFILE_A]);

        $validator->validate(new Reference(reference: 'Patient/1'), $constraint);
        $this->assertNoViolation();

        $validator->validate(new Reference(reference: 'Organization/1'), $constraint);
        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'Organization', '{{ allowed }}' => 'Patient'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * Core profiles such as `vitalsigns` are generated with the models, so they are known with no
     * registry configured.
     */
    public function testCoreProfileTargetIsReadForItsBaseType(): void
    {
        $constraint = new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/vitalsigns', 'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse']);

        $this->validator->validate(new Reference(reference: 'Observation/bp'), $constraint);
        $this->validator->validate(new Reference(reference: 'QuestionnaireResponse/1'), $constraint);
        $this->assertNoViolation();

        $this->validator->validate(new Reference(reference: 'Patient/1'), $constraint);
        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'Patient', '{{ allowed }}' => 'Observation, QuestionnaireResponse'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A profile known only as a loaded StructureDefinition is read for its `type`, in the release the
     * Reference class belongs to.
     */
    public function testRuntimeProfileTargetIsReadForItsBaseType(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([[
            'resourceType' => 'StructureDefinition',
            'url'          => self::PROFILE_B,
            'fhirVersion'  => '4.0.1',
            'kind'         => 'resource',
            'type'         => 'Organization',
            'derivation'   => 'constraint',
        ]]);
        $validator = $this->validatorWith(new TargetProfileBaseTypes(runtimeProfiles: new RuntimeProfileResolver($registry)));

        $validator->validate(new Reference(reference: 'Organization/1'), new FHIRTargetProfile([self::PROFILE_B]));
        $validator->validate(new Reference(reference: 'Practitioner/2'), new FHIRTargetProfile([self::PROFILE_B]));

        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'Practitioner', '{{ allowed }}' => 'Organization'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * One unknown target could allow any type, so the whole check is skipped rather than run against
     * the targets that are known.
     */
    public function testAnUnknownProfileAmongTheTargetsSkipsTheCheck(): void
    {
        $this->validator->validate(
            new Reference(reference: 'Practitioner/2'),
            new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/vitalsigns', self::UNKNOWN_PROFILE]),
        );

        $this->assertNoViolation();
    }

    public function testCanonicalIsCheckedAgainstACoreProfileTarget(): void
    {
        $canonical = new CanonicalPrimitive(value: 'http://example.org/fhir/ValueSet/vs1');

        $this->validator->validate($canonical, new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/cqf-questionnaire']));

        $this->buildViolation(FHIRTargetProfileValidator::CANONICAL_TYPE_MESSAGE)
            ->setParameters([
                '{{ canonical }}' => 'http://example.org/fhir/ValueSet/vs1',
                '{{ type }}'      => 'ValueSet',
                '{{ allowed }}'   => 'Questionnaire',
            ])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A rule naming a kind checks only that kind of value: under `Reference(Group) |
     * canonical(EvidenceVariable)`, a Group canonical and an EvidenceVariable reference are each
     * judged by their own kind's rule (#246).
     */
    public function testRuleNamingAKindSkipsValuesOfTheOtherKind(): void
    {
        $referenceRule = new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Group'], kind: FHIRTargetProfile::KIND_REFERENCE);
        $canonicalRule = new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/EvidenceVariable'], kind: FHIRTargetProfile::KIND_CANONICAL);

        $this->validator->validate(new CanonicalPrimitive(value: 'http://example.org/fhir/EvidenceVariable/ev1'), $referenceRule);
        $this->validator->validate(new Reference(reference: 'EvidenceVariable/ev1'), $canonicalRule);
        $this->validator->validate(new CanonicalPrimitive(value: 'http://example.org/fhir/EvidenceVariable/ev1'), $canonicalRule);
        $this->validator->validate(new Reference(reference: 'Group/g1'), $referenceRule);
        $this->assertNoViolation();

        $this->validator->validate(new Reference(reference: 'EvidenceVariable/ev1'), $referenceRule);
        $this->buildViolation(FHIRTargetProfileValidator::REFERENCE_TYPE_MESSAGE)
            ->setParameters(['{{ type }}' => 'EvidenceVariable', '{{ allowed }}' => 'Group'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testUnknownKindIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FHIRTargetProfile(['http://hl7.org/fhir/StructureDefinition/Group'], kind: 'reference');
    }

    private function validatorWith(TargetProfileBaseTypes $profileTypes): FHIRTargetProfileValidator
    {
        $validator = new FHIRTargetProfileValidator(new NullFHIRReferenceResolver(), new FHIRValidationMessageRegistry(), profileTypes: $profileTypes);
        $this->bindContext($validator);

        return $validator;
    }
}
