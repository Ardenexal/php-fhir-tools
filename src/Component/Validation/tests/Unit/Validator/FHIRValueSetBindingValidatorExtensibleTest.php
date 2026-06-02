<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Verifies extensible/preferred binding validation behaviour:
 * - null client or NullFHIRTerminologyClient → fhir:unchecked-binding INFO violation (issue #71)
 * - real client returning true → no violation
 * - real client returning false → WARNING violation
 * - required + no enum + real client → falls back to terminology server
 * - required + no enum + no client → WARNING (tooling gap)
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorExtensibleTest extends ConstraintValidatorTestCase
{
    private const string VS_URL = 'http://hl7.org/fhir/ValueSet/languages';

    /** @var MockObject&FHIRTerminologyClientInterface */
    private MockObject $mockClient;

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(FHIRTerminologyClientInterface::class);
        parent::setUp();
    }

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
    }

    // -------------------------------------------------------------------------
    // Null client (no terminology server configured)
    // -------------------------------------------------------------------------

    public function testExtensibleBindingWithNullClientProducesUncheckedBindingInfoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testPreferredBindingWithNullClientProducesUncheckedBindingInfoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'preferred'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testExtensibleBindingWithNullObjectClientProducesUncheckedBindingInfoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], new NullFHIRTerminologyClient());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testExampleBindingWithNullClientProducesNoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'example'));

        $this->assertNoViolation();
    }

    public function testExampleBindingWithRealClientIsNeverValidated(): void
    {
        $this->mockClient->expects(self::never())->method('validateCode');

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'example'));

        $this->assertNoViolation();
    }

    public function testExtensibleBindingWithNullClientAndNullValueProducesNoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate(null, new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->assertNoViolation();
    }

    public function testUncheckedBindingMessageHonoursRegistryOverride(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRValueSetBindingUnchecked', 'Custom skip notice for {{ url }}');

        $validator = new FHIRValueSetBindingValidator($registry);
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->buildViolation('Custom skip notice for {{ url }}')
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testNullTerminologyClientAlwaysReturnsTrue(): void
    {
        $client = new NullFHIRTerminologyClient();

        self::assertTrue($client->validateCode(self::VS_URL, 'en'));
        self::assertTrue($client->validateCode(self::VS_URL, 'not-a-real-code'));
        self::assertTrue($client->validateCode(self::VS_URL, null));
    }

    // -------------------------------------------------------------------------
    // Real client returning true → no violation
    // -------------------------------------------------------------------------

    public function testExtensibleBindingValidCodeWithRealClientProducesNoViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(true);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('en', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->assertNoViolation();
    }

    public function testPreferredBindingValidCodeWithRealClientProducesNoViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(true);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('en', new FHIRValueSetBinding(self::VS_URL, 'preferred'));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Real client returning false → WARNING violation
    // -------------------------------------------------------------------------

    public function testExtensibleBindingInvalidCodeWithRealClientProducesWarningViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('not-a-language', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'not-a-language', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('not-a-language')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testPreferredBindingInvalidCodeWithRealClientProducesWarningViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('bad-code', new FHIRValueSetBinding(self::VS_URL, 'preferred'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'bad-code', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('bad-code')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // -------------------------------------------------------------------------
    // Required + no enum → terminology server fallback
    // -------------------------------------------------------------------------

    public function testRequiredBindingNoEnumWithClientValidCodeProducesNoViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(true);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('valid-code', new FHIRValueSetBinding(self::VS_URL, 'required'));

        $this->assertNoViolation();
    }

    public function testRequiredBindingNoEnumWithClientInvalidCodeProducesErrorViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('bad-code', new FHIRValueSetBinding(self::VS_URL, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'bad-code', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('bad-code')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRequiredBindingNoEnumWithClientValidatesEachArrayItem(): void
    {
        $this->mockClient
            ->method('validateCode')
            ->willReturnMap([[self::VS_URL, 'good', true], [self::VS_URL, 'bad', false]]);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate(['good', 'bad'], new FHIRValueSetBinding(self::VS_URL, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'bad', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('bad')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRequiredBindingNoEnumWithoutClientProducesWarning(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $url = 'http://hl7.org/fhir/ValueSet/unknown-required-vs';
        $validator->validate('anything', new FHIRValueSetBinding($url, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_MISSING_ENUM_MESSAGE)
            ->setParameters(['{{ url }}' => $url])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }
}
