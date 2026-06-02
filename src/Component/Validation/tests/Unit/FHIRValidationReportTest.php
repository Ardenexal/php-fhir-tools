<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use PHPUnit\Framework\TestCase;

final class FHIRValidationReportTest extends TestCase
{
    private static function violation(string $severity, ?string $code = null): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: $severity,
            path: 'identifier',
            message: 'test message',
            constraintClass: 'SomeConstraint',
            profileGroup: null,
            invariantKey: null,
            code: $code,
        );
    }

    public function testEmptyReportIsValid(): void
    {
        $report = new FHIRValidationReport([]);

        self::assertTrue($report->isValid());
        self::assertFalse($report->hasErrors());
        self::assertFalse($report->hasWarnings());
        self::assertSame([], $report->errors());
        self::assertSame([], $report->warnings());
        self::assertSame([], $report->info());
    }

    public function testReportWithOnlyWarningsIsValid(): void
    {
        $report = new FHIRValidationReport([self::violation('warning')]);

        self::assertTrue($report->isValid());
        self::assertFalse($report->hasErrors());
        self::assertTrue($report->hasWarnings());
    }

    public function testReportWithOnlyInfoIsValid(): void
    {
        $report = new FHIRValidationReport([self::violation('info')]);

        self::assertTrue($report->isValid());
        self::assertFalse($report->hasErrors());
        self::assertFalse($report->hasWarnings());
        self::assertCount(1, $report->info());
    }

    public function testReportWithErrorIsInvalid(): void
    {
        $report = new FHIRValidationReport([self::violation('error')]);

        self::assertFalse($report->isValid());
        self::assertTrue($report->hasErrors());
    }

    public function testErrorsFilterReturnsOnlyErrors(): void
    {
        $error   = self::violation('error');
        $warning = self::violation('warning');
        $info    = self::violation('info');

        $report = new FHIRValidationReport([$error, $warning, $info]);

        self::assertSame([$error], $report->errors());
        self::assertSame([$warning], $report->warnings());
        self::assertSame([$info], $report->info());
    }

    public function testMultipleErrorsAllReturned(): void
    {
        $e1 = self::violation('error');
        $e2 = self::violation('error');

        $report = new FHIRValidationReport([$e1, $e2]);

        self::assertCount(2, $report->errors());
        self::assertTrue($report->hasErrors());
        self::assertFalse($report->isValid());
    }

    public function testInfoDoesNotAffectWarningOrErrorFlags(): void
    {
        $report = new FHIRValidationReport([self::violation('info')]);

        self::assertFalse($report->hasErrors());
        self::assertFalse($report->hasWarnings());
        self::assertCount(1, $report->info());
    }

    public function testEmptyReportHasNoUncheckedBindings(): void
    {
        $report = new FHIRValidationReport([]);

        self::assertFalse($report->hasUncheckedBindings());
        self::assertSame([], $report->uncheckedBindings());
    }

    public function testUncheckedBindingsFilterByViolationCode(): void
    {
        $unchecked = self::violation('info', FHIRViolationCode::UNCHECKED_BINDING);
        $plainInfo = self::violation('info', FHIRViolationCode::INFO);
        $error     = self::violation('error', FHIRViolationCode::ERROR);

        $report = new FHIRValidationReport([$unchecked, $plainInfo, $error]);

        self::assertTrue($report->hasUncheckedBindings());
        self::assertSame([$unchecked], $report->uncheckedBindings());
    }

    public function testReportWithOnlyUncheckedBindingsIsStillValid(): void
    {
        $report = new FHIRValidationReport([self::violation('info', FHIRViolationCode::UNCHECKED_BINDING)]);

        self::assertTrue($report->isValid());
        self::assertFalse($report->hasErrors());
        self::assertFalse($report->hasWarnings());
        self::assertCount(1, $report->info());
        self::assertTrue($report->hasUncheckedBindings());
    }
}
