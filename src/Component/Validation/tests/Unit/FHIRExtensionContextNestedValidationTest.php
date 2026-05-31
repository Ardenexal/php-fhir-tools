<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\DeferredContextFailingInvariantExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ForeignRootOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\NestedContactWithExtensionsFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientContactOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientNameOnlyExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientPermittedExtensionFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\PatientWithContactResourceFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FHIRExtensionContextNestedValidationTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    public function testNestedExtensionRestrictedToSiblingPathProducesError(): void
    {
        // PatientNameOnlyExtensionFixture context is "Patient.name".
        // Placed on Patient.contact — a sibling path — it must produce a violation.
        $contact  = new NestedContactWithExtensionsFixture([new PatientNameOnlyExtensionFixture()]);
        $resource = new PatientWithContactResourceFixture(contact: [$contact]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Extension restricted to Patient.name must fail on Patient.contact');

        $violation = $report->errors()[0];
        self::assertSame('error', $violation->severity);
        self::assertSame('contact[0].extension', $violation->path);
        self::assertSame(FHIRExtensionContext::class, $violation->constraintClass);
        self::assertStringContainsString('Patient.contact', $violation->message);
        self::assertStringContainsString('http://example.org/ext/patient-name-only', $violation->message);
    }

    public function testNestedExtensionMatchingContactPathProducesNoViolation(): void
    {
        // PatientContactOnlyExtensionFixture context is "Patient.contact".
        // Placed on Patient.contact — it must be permitted.
        $contact  = new NestedContactWithExtensionsFixture([new PatientContactOnlyExtensionFixture()]);
        $resource = new PatientWithContactResourceFixture(contact: [$contact]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Extension permitted on Patient.contact must produce no violation');
    }

    public function testNestedExtensionWithBareResourceTypeContextIsDeferredProducesNoViolation(): void
    {
        // PatientPermittedExtensionFixture context is "Patient" (no dot).
        // Bare-type contexts at sub-element level are deferred (require type-hierarchy resolution).
        $contact  = new NestedContactWithExtensionsFixture([new PatientPermittedExtensionFixture()]);
        $resource = new PatientWithContactResourceFixture(contact: [$contact]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Bare resource-type contexts at sub-element level must be deferred');
    }

    public function testMultipleContactsOnlyForbiddenOneProducesOneErrorWithCorrectIndex(): void
    {
        $permitted = new NestedContactWithExtensionsFixture([new PatientContactOnlyExtensionFixture()]);
        $forbidden = new NestedContactWithExtensionsFixture([new PatientNameOnlyExtensionFixture()]);
        $resource  = new PatientWithContactResourceFixture(contact: [$permitted, $forbidden]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors());
        self::assertSame('contact[1].extension', $report->errors()[0]->path);
    }

    public function testResourceWithEmptyContactArrayProducesNoViolations(): void
    {
        $resource = new PatientWithContactResourceFixture();

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors());
    }

    public function testForeignRootOnlyContextIsDeferredWithoutResolver(): void
    {
        // ForeignRootOnlyExtensionFixture context is "Observation.component" only (foreign root).
        // Foreign-root contexts require type-path resolution to evaluate correctly — the context
        // "Observation.component" could be valid if the current element is typed as Observation.component.
        // Without a resolver, this cannot be determined, so the check is deferred (no violation).
        $contact  = new NestedContactWithExtensionsFixture([new ForeignRootOnlyExtensionFixture()]);
        $resource = new PatientWithContactResourceFixture(contact: [$contact]);

        $report = $this->service->validate($resource);

        self::assertCount(0, $report->errors(), 'Foreign-root contexts without a resolver must be deferred (no violation)');
    }

    public function testDeferredContextExtensionWithFailingInvariantProducesErrorAtNestedLevel(): void
    {
        // Extension context is "Patient" (bare type, deferred at sub-element level).
        // contextInvariant "1 = 2" must still be evaluated and must produce a violation.
        $contact  = new NestedContactWithExtensionsFixture([new DeferredContextFailingInvariantExtensionFixture()]);
        $resource = new PatientWithContactResourceFixture(contact: [$contact]);

        $report = $this->service->validate($resource);

        self::assertCount(1, $report->errors(), 'Failing contextInvariant must fire even when context check is deferred');

        $violation = $report->errors()[0];
        self::assertSame('error', $violation->severity);
        self::assertSame('contact[0].extension', $violation->path);
        self::assertSame(FHIRContextInvariant::class, $violation->constraintClass);
        self::assertStringContainsString('1 = 2', $violation->message);
    }
}
