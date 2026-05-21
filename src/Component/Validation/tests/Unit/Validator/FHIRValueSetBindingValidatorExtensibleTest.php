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
 * - null client → no violation (graceful degradation)
 * - real client returning true → no violation
 * - real client returning false → WARNING violation
 * - required strength is unaffected by the client
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

    public function testExtensibleBindingWithNullClientProducesNoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'extensible'));

        $this->assertNoViolation();
    }

    public function testPreferredBindingWithNullClientProducesNoViolation(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'preferred'));

        $this->assertNoViolation();
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
    // Required strength is unaffected by the client
    // -------------------------------------------------------------------------

    public function testRequiredBindingWithRealClientStillUsesEnumPath(): void
    {
        // Client should NOT be called for required — enum class lookup governs required
        $this->mockClient->expects(self::never())->method('validateCode');

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        // No enum class found → DEFAULT_MISSING_ENUM_MESSAGE (existing required behavior)
        $url = 'http://hl7.org/fhir/ValueSet/unknown-required-vs';
        $validator->validate('anything', new FHIRValueSetBinding($url, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_MISSING_ENUM_MESSAGE)
            ->setParameters(['{{ url }}' => $url])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
