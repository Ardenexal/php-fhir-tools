<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource as R4Outcome;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcomeResource as R4BOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource as R5Outcome;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReportMapper;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use PHPUnit\Framework\TestCase;

final class FHIRValidationReportMapperTest extends TestCase
{
    private FHIRValidationReportMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new FHIRValidationReportMapper();
    }

    private static function violation(
        string $severity,
        string $constraintClass = '',
        string $path = '',
        ?string $code = null,
    ): FHIRValidationViolation {
        return new FHIRValidationViolation(
            severity: $severity,
            path: $path,
            message: 'test message',
            constraintClass: $constraintClass,
            profileGroup: null,
            invariantKey: null,
            code: $code,
        );
    }

    public function testEmptyReportProducesInformationIssue(): void
    {
        $report  = new FHIRValidationReport([]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertInstanceOf(R4Outcome::class, $outcome);
        self::assertCount(1, $outcome->issue);
        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertStringContainsString('No issues found', (string) $outcome->issue[0]->diagnostics);
    }

    public function testErrorAndWarningSeveritiesMapCorrectly(): void
    {
        $report = new FHIRValidationReport([
            self::violation('error'),
            self::violation('warning'),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertInstanceOf(R4Outcome::class, $outcome);
        self::assertCount(2, $outcome->issue);
        self::assertSame('error', $outcome->issue[0]->severity?->value);
        self::assertSame('warning', $outcome->issue[1]->severity?->value);
    }

    public function testInfoSeverityMapsToInformation(): void
    {
        $report  = new FHIRValidationReport([self::violation('info')]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('information', $outcome->issue[0]->severity?->value);
    }

    public function testEvalErrorViolationMapsToInformationSeverityNotSupported(): void
    {
        $report = new FHIRValidationReport([
            self::violation('info', code: FHIRViolationCode::EVAL_ERROR),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertSame('not-supported', $outcome->issue[0]->code?->value);
    }

    public function testUncheckedBindingViolationMapsToNotSupported(): void
    {
        $report = new FHIRValidationReport([
            self::violation('info', code: FHIRViolationCode::UNCHECKED_BINDING),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertSame('not-supported', $outcome->issue[0]->code?->value);
    }

    public function testGeneralInfoViolationMapsToInformational(): void
    {
        $report = new FHIRValidationReport([
            self::violation('info', code: FHIRViolationCode::INFO),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('information', $outcome->issue[0]->severity?->value);
        self::assertSame('informational', $outcome->issue[0]->code?->value);
    }

    public function testFHIRPathInvariantConstraintMapsToInvariantCode(): void
    {
        $report = new FHIRValidationReport([
            self::violation('error', constraintClass: FHIRPathInvariant::class),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('invariant', $outcome->issue[0]->code?->value);
    }

    public function testFHIRValueSetBindingConstraintMapsToValueCode(): void
    {
        $report = new FHIRValidationReport([
            self::violation('error', constraintClass: FHIRValueSetBinding::class),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('value', $outcome->issue[0]->code?->value);
    }

    public function testDefaultConstraintMapsToInvalidCode(): void
    {
        $report = new FHIRValidationReport([
            self::violation('error', constraintClass: 'SomeOtherConstraint'),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame('invalid', $outcome->issue[0]->code?->value);
    }

    public function testViolationWithPathPopulatesExpression(): void
    {
        $report = new FHIRValidationReport([
            self::violation('error', path: 'identifier[0].system'),
        ]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame(['identifier[0].system'], $outcome->issue[0]->expression);
    }

    public function testViolationWithEmptyPathProducesEmptyExpression(): void
    {
        $report  = new FHIRValidationReport([self::violation('error', path: '')]);
        $outcome = $this->mapper->toOperationOutcome($report);

        self::assertSame([], $outcome->issue[0]->expression);
    }

    public function testR4VersionProducesR4OutcomeClass(): void
    {
        $report = new FHIRValidationReport([]);
        self::assertInstanceOf(R4Outcome::class, $this->mapper->toOperationOutcome($report, 'R4'));
    }

    public function testR4BVersionProducesR4BOutcomeClass(): void
    {
        $report = new FHIRValidationReport([]);
        self::assertInstanceOf(R4BOutcome::class, $this->mapper->toOperationOutcome($report, 'R4B'));
    }

    public function testR5VersionProducesR5OutcomeClass(): void
    {
        $report = new FHIRValidationReport([]);
        self::assertInstanceOf(R5Outcome::class, $this->mapper->toOperationOutcome($report, 'R5'));
    }

    public function testUnknownVersionThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/R9/');

        $this->mapper->toOperationOutcome(new FHIRValidationReport([]), 'R9');
    }

    public function testDiagnosticsContainsViolationMessage(): void
    {
        $violation = new FHIRValidationViolation(
            severity: 'error',
            path: '',
            message: 'Name is required',
            constraintClass: '',
            profileGroup: null,
            invariantKey: null,
        );
        $outcome = $this->mapper->toOperationOutcome(new FHIRValidationReport([$violation]));

        self::assertSame('Name is required', (string) $outcome->issue[0]->diagnostics);
    }
}
