<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\FailingInvariantExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\FhirpathContextExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\NoContextExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\NoExtensionsTraitFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ObservationOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PassingInvariantExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientExtensionResourceFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientPermittedExtensionFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FHIRExtensionContextValidationTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    // --- resource without extensions trait ---

    public function testResourceWithoutGetExtensionsMethodProducesNoContextViolations(): void
    {
        $resource = new NoExtensionsTraitFixture();

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors());
    }

    // --- context attribute checking ---

    public function testExtensionPermittedOnMatchingResourceTypeProducesNoViolation(): void
    {
        $resource = new PatientExtensionResourceFixture([new PatientPermittedExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Extension permitted on Patient must not produce a violation');
    }

    public function testExtensionForbiddenOnNonMatchingResourceTypeProducesError(): void
    {
        $resource = new PatientExtensionResourceFixture([new ObservationOnlyExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Extension only permitted on Observation must fail on Patient');

        $violation = $report->errors()[0];
        self::assertSame('error', $violation->severity);
        self::assertSame('extension', $violation->path);
        self::assertSame(FHIRExtensionContext::class, $violation->constraintClass);
        self::assertStringContainsString('http://example.org/ext/observation-only', $violation->message);
        self::assertStringContainsString('Patient', $violation->message);
    }

    public function testFhirpathContextTypeIsDeferredAndProducesNoViolation(): void
    {
        $resource = new PatientExtensionResourceFixture([new FhirpathContextExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'fhirpath context type must be treated as permitted in v1');
    }

    public function testExtensionWithNoContextAttributeProducesNoViolation(): void
    {
        $resource = new PatientExtensionResourceFixture([new NoContextExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Extension with no context attrs must produce no context violation');
    }

    public function testResourceWithNoExtensionsProducesNoViolation(): void
    {
        $resource = new PatientExtensionResourceFixture([]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors());
    }

    // --- contextInvariant checking ---

    public function testPassingContextInvariantProducesNoViolation(): void
    {
        $resource = new PatientExtensionResourceFixture([new PassingInvariantExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Passing contextInvariant must not produce a violation');
    }

    public function testFailingContextInvariantProducesError(): void
    {
        $resource = new PatientExtensionResourceFixture([new FailingInvariantExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Failing contextInvariant must produce an error');

        $violation = $report->errors()[0];
        self::assertSame('error', $violation->severity);
        self::assertSame('extension', $violation->path);
        self::assertSame(FHIRContextInvariant::class, $violation->constraintClass);
        self::assertStringContainsString('1 = 2', $violation->message);
    }

    // --- extension context violations flow into errors() ---

    public function testContextViolationsFlowIntoFHIRValidationReport(): void
    {
        $resource = new PatientExtensionResourceFixture([new ObservationOnlyExtensionFixture()]);

        $report = $this->service->validate($resource);

        self::assertFalse($report->isValid(), 'Report with extension context error must not be valid');
        self::assertCount(1, $report->errors());
        self::assertCount(0, $report->warnings());
        self::assertCount(0, $report->info());
    }
}
