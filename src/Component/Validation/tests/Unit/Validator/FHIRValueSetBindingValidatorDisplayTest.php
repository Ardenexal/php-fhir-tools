<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Fixture enum for this file only; see the sibling Coding test for why it is not shared.
 */
enum DisplayBindingStatus: string
{
    case active   = 'active';
    case inactive = 'inactive';
}

/**
 * A coding whose display the terminology server rejects is reported as a warning.
 *
 * The display question is separate from membership: a code can belong to the value set and still carry
 * the wrong label. It is answered by asking the server, never by this codebase, because a display can be
 * right in one declared language and wrong in another and only the server knows.
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorDisplayTest extends ConstraintValidatorTestCase
{
    private const string VS = 'http://test.example/ValueSet/display-binding-status';

    private const string SYSTEM = 'http://test.example/CodeSystem/status';

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [__NAMESPACE__]);
    }

    public function testRejectedDisplayIsReportedAsAWarning(): void
    {
        $this->useClient($this->clientCorrecting('Active'));

        $this->validator->validate(
            $this->concept('active', 'Wrong Label'),
            new FHIRValueSetBinding(self::VS),
        );

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_WRONG_DISPLAY_MESSAGE)
            ->setParameters([
                '{{ value }}'    => 'Wrong Label',
                '{{ code }}'     => 'active',
                '{{ system }}'   => self::SYSTEM,
                '{{ expected }}' => 'Active',
            ])
            ->setInvalidValue('Wrong Label')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    /**
     * The server accepting a display is the end of it, whatever label it would have chosen. This is the
     * shape that used to be reported and is the reason the client's mismatch rule changed.
     */
    public function testAcceptedDisplayIsSilent(): void
    {
        $this->useClient($this->clientCorrecting(null));

        $this->validator->validate(
            $this->concept('active', 'Aktiv'),
            new FHIRValueSetBinding(self::VS),
        );

        $this->assertNoViolation();
    }

    /** A coding that carries no display has made no claim to check. */
    public function testCodingWithoutADisplayIsNotAsked(): void
    {
        $this->useClient($this->failingClient());

        $this->validator->validate(
            $this->concept('active', null),
            new FHIRValueSetBinding(self::VS),
        );

        $this->assertNoViolation();
    }

    /** A display cannot be judged without the system that gives its code meaning. */
    public function testCodingWithoutASystemIsNotAsked(): void
    {
        $this->useClient($this->failingClient());

        $coding = new Coding(code: new CodePrimitive(value: 'active'), display: new StringPrimitive(value: 'Whatever'));

        $this->validator->validate(new CodeableConcept(coding: [$coding]), new FHIRValueSetBinding(self::VS));

        $this->assertNoViolation();
    }

    /**
     * A caller who configures nothing must see exactly what they saw before. Display text is not
     * derivable from anything this repository holds, so without a client there is no question to ask.
     */
    public function testNoClientMeansNoDisplayFinding(): void
    {
        $this->useClient(new NullFHIRTerminologyClient());

        $this->validator->validate(
            $this->concept('active', 'Wrong Label'),
            new FHIRValueSetBinding(self::VS, strength: 'preferred'),
        );

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    /** Example-strength bindings are documentation only (ADR-004), display included. */
    public function testExampleStrengthBindingIsNotAsked(): void
    {
        $this->useClient($this->failingClient());

        $this->validator->validate(
            $this->concept('active', 'Wrong Label'),
            new FHIRValueSetBinding(self::VS, strength: 'example'),
        );

        $this->assertNoViolation();
    }

    /** Every coding in a concept is asked about on its own. */
    public function testEachCodingInAConceptIsAsked(): void
    {
        $this->useClient($this->clientCorrecting('Active'));

        $value = new CodeableConcept(coding: [
            $this->coding('active', 'Wrong One'),
            $this->coding('active', 'Wrong Two'),
        ]);

        $this->validator->validate($value, new FHIRValueSetBinding(self::VS));

        self::assertCount(2, $this->context->getViolations());
    }

    private function useClient(FHIRTerminologyClientInterface $client): void
    {
        $validator = new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            [__NAMESPACE__],
            $client,
        );
        $validator->initialize($this->context);
        $this->validator = $validator;
    }

    /** Accepts every code; returns $correction as the display verdict, or none when null. */
    private function clientCorrecting(?string $correction): FHIRTerminologyClientInterface
    {
        return new InMemoryFHIRTerminologyClient(
            map: [self::VS => [self::SYSTEM . '|active' => true, self::SYSTEM . '|inactive' => true]],
            displayMap: $correction === null ? [] : [self::VS => [self::SYSTEM . '|active' => $correction]],
        );
    }

    /** A client that would report a correction if it were ever asked, so silence is meaningful. */
    private function failingClient(): FHIRTerminologyClientInterface
    {
        return $this->clientCorrecting('Active');
    }

    private function concept(string $code, ?string $display): CodeableConcept
    {
        return new CodeableConcept(coding: [$this->coding($code, $display)]);
    }

    private function coding(string $code, ?string $display): Coding
    {
        return new Coding(
            system: new UriPrimitive(value: self::SYSTEM),
            code: new CodePrimitive(value: $code),
            display: $display === null ? null : new StringPrimitive(value: $display),
        );
    }
}
