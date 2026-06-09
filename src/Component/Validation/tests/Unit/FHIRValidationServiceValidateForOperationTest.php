<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource as R4Outcome;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcomeResource as R4BOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource as R5Outcome;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

final class FHIRValidationServiceValidateForOperationTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    public function testReturnsR4OutcomeByDefault(): void
    {
        $outcome = $this->service->validateForOperation(new \stdClass());

        self::assertInstanceOf(R4Outcome::class, $outcome);
    }

    public function testReturnsR4BOutcomeForR4BVersion(): void
    {
        $outcome = $this->service->validateForOperation(new \stdClass(), fhirVersion: 'R4B');

        self::assertInstanceOf(R4BOutcome::class, $outcome);
    }

    public function testReturnsR5OutcomeForR5Version(): void
    {
        $outcome = $this->service->validateForOperation(new \stdClass(), fhirVersion: 'R5');

        self::assertInstanceOf(R5Outcome::class, $outcome);
    }

    public function testDeleteModeReturnsInformationIssueWithoutValidating(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects(self::never())->method('validate');
        $service = new FHIRValidationService($validator, new FHIRPathService());

        $outcome = $service->validateForOperation(new \stdClass(), mode: 'delete');

        self::assertInstanceOf(R4Outcome::class, $outcome);
        self::assertCount(1, $outcome->issue);
        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertSame('not-supported', $outcome->issue[0]->code?->value);
        self::assertStringContainsString('delete mode', (string) $outcome->issue[0]->diagnostics);
    }

    public function testInvalidModeThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Create/');

        $this->service->validateForOperation(new \stdClass(), mode: 'Create');
    }

    public function testValidResourceProducesNoIssueOutcome(): void
    {
        $outcome = $this->service->validateForOperation(new \stdClass());

        self::assertInstanceOf(R4Outcome::class, $outcome);
        self::assertCount(1, $outcome->issue);
        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertStringContainsString('No issues found', (string) $outcome->issue[0]->diagnostics);
    }
}
