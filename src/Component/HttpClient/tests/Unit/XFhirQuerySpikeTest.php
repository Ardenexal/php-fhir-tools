<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\TestCase;

/**
 * M01 gating spike (kill-criteria gate): proves the FHIRPath engine can (a) bind `%`-prefixed
 * launch-context constants and (b) evaluate the date arithmetic the canonical x-fhir-query spec
 * example needs (`{{today()-7 days}}`). If either fails, x-fhir-query resolution is not buildable
 * on the existing engine (M01 kill criteria) — do NOT build a second evaluator.
 *
 * @internal
 */
final class XFhirQuerySpikeTest extends TestCase
{
    private FHIRPathService $fhirPath;

    protected function setUp(): void
    {
        $this->fhirPath = new FHIRPathService();
    }

    public function testExternalConstantBindingResolvesPatientId(): void
    {
        $context = (new EvaluationContext())
            ->withExternalConstant('patient', ['resourceType' => 'Patient', 'id' => '123']);

        $result = $this->fhirPath->evaluate('%patient.id', null, $context);

        self::assertSame(['123'], $result->toArray());
    }

    public function testTodayIsSupported(): void
    {
        $result = $this->fhirPath->evaluate('today()', null);

        self::assertFalse($result->isEmpty(), 'today() must resolve for the spec date example to work');
    }

    public function testTodayMinusSevenDaysArithmeticIsSupported(): void
    {
        $result = $this->fhirPath->evaluate('today() - 7 days', null);

        self::assertFalse($result->isEmpty(), 'date-quantity arithmetic must resolve for {{today()-7 days}}');
    }
}
