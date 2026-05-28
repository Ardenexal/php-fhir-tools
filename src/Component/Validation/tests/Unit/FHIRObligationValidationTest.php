<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ObligationFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;

final class FHIRObligationValidationTest extends TestCase
{
    private const string PLACER = 'http://example.org/actor/placer';

    private const string FILLER = 'http://example.org/actor/filler';

    /** @var ValidatorInterface&MockObject */
    private ValidatorInterface $validator;

    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $this->validator = $this->createStub(ValidatorInterface::class);
        $this->validator->method('validate')->willReturn(new ConstraintViolationList());
        $this->service = new FHIRValidationService($this->validator, new FHIRPathService());
    }

    // --- no obligation context ---

    public function testNoObligationContextProducesNoObligationViolations(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource);

        self::assertCount(0, $report->violations);
    }

    // --- SHALL:populate ---

    public function testShallPopulateNullElementProducesErrorForMatchingActor(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertContains('shallPopulate', $errorPaths);
    }

    public function testShallPopulateNonNullElementProducesNoViolation(): void
    {
        $resource = new ObligationFixture(shallPopulate: 'value');
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertNotContains('shallPopulate', $errorPaths);
    }

    public function testShallPopulateNonMatchingActorProducesNoViolation(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::FILLER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertNotContains('shallPopulate', $errorPaths);
    }

    // --- SHOULD:populate ---

    public function testShouldPopulateNullElementProducesWarningForMatchingActor(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $warningPaths = array_map(static fn ($v) => $v->path, $report->warnings());
        self::assertContains('shouldPopulate', $warningPaths);
    }

    // --- SHALL:populate-if-known ---

    public function testShallPopulateIfKnownNullElementProducesInfoForMatchingActor(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $infoPaths = array_map(static fn ($v) => $v->path, $report->info());
        self::assertContains('shallPopulateIfKnown', $infoPaths);
    }

    // --- null actor obligation (all actors) ---

    public function testNullActorObligationFiresForAnyContextActor(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::FILLER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertContains('shallPopulateAllActors', $errorPaths);
    }

    public function testNullActorObligationFiresForNullContextActor(): void
    {
        $resource = new ObligationFixture();
        $report   = $this->service->validate($resource, obligationContext: new FHIRObligationContext(null));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertContains('shallPopulateAllActors', $errorPaths);
    }

    // --- SHALL:no-error suppression ---

    public function testShallNoErrorSuppressesErrorViolationsForMatchingActorProperty(): void
    {
        $mockValidator = $this->createStub(ValidatorInterface::class);
        $mockValidator->method('validate')->willReturn(new ConstraintViolationList([
            new ConstraintViolation(
                message: 'Cardinality failed.',
                messageTemplate: 'Cardinality failed.',
                parameters: [],
                root: new \stdClass(),
                propertyPath: 'noErrorProperty',
                invalidValue: null,
                plural: null,
                code: FHIRViolationCode::ERROR,
                constraint: null,
            ),
        ]));

        $service  = new FHIRValidationService($mockValidator, new FHIRPathService());
        $resource = new ObligationFixture();
        $report   = $service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertNotContains('noErrorProperty', $errorPaths);
    }

    public function testShallNoErrorDoesNotSuppressWhenActorDoesNotMatch(): void
    {
        $mockValidator = $this->createStub(ValidatorInterface::class);
        $mockValidator->method('validate')->willReturn(new ConstraintViolationList([
            new ConstraintViolation(
                message: 'Cardinality failed.',
                messageTemplate: 'Cardinality failed.',
                parameters: [],
                root: new \stdClass(),
                propertyPath: 'noErrorProperty',
                invalidValue: null,
                plural: null,
                code: FHIRViolationCode::ERROR,
                constraint: null,
            ),
        ]));

        $service  = new FHIRValidationService($mockValidator, new FHIRPathService());
        $resource = new ObligationFixture();
        $report   = $service->validate($resource, obligationContext: new FHIRObligationContext(self::FILLER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertContains('noErrorProperty', $errorPaths);
    }

    // --- filter sub-extension ---

    public function testFilteredObligationIsSkippedWithoutError(): void
    {
        $resource = new ObligationFixture();

        // Must not throw; filtered obligation is silently skipped
        $report = $this->service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $errorPaths = array_map(static fn ($v) => $v->path, $report->errors());
        self::assertNotContains('filteredObligation', $errorPaths);
    }
}
