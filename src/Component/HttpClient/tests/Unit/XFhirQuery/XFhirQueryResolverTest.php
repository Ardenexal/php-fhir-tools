<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit\XFhirQuery;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\HttpClient\XFhirQuery\XFhirQueryResolver;
use PHPUnit\Framework\TestCase;

final class XFhirQueryResolverTest extends TestCase
{
    private XFhirQueryResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new XFhirQueryResolver();
    }

    public function testNoHolesPassesThroughUnchanged(): void
    {
        $out = $this->resolver->resolve('Patient?name=smith&active=true', new EvaluationContext());

        self::assertSame('Patient?name=smith&active=true', $out);
    }

    public function testScalarSubstitutionIsDeterministic(): void
    {
        $context = (new EvaluationContext())
            ->withExternalConstant('patient', ['resourceType' => 'Patient', 'id' => '123']);

        $out = $this->resolver->resolve('Observation?subject={{%patient.id}}', $context);

        self::assertSame('Observation?subject=123', $out);
    }

    public function testMultipleValuesAreCommaJoinedAsOr(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('patient', [
            'resourceType' => 'Patient',
            'identifier'   => [
                ['system' => 'urn:a', 'value' => '111'],
                ['system' => 'urn:b', 'value' => '222'],
            ],
        ]);

        $out = $this->resolver->resolve('Patient?identifier={{%patient.identifier.value}}', $context);

        self::assertSame('Patient?identifier=111,222', $out);
    }

    public function testLiteralPrefixIsKeptAndHoleSubstituted(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('cutoff', '2020-01-01');

        $out = $this->resolver->resolve('Observation?date=gt{{%cutoff}}', $context);

        self::assertSame('Observation?date=gt2020-01-01', $out);
    }

    /**
     * The canonical R5 spec example. The date is asserted structurally because {{today()-7 days}} is
     * time-dependent — a hardcoded date would be a time-bomb.
     */
    public function testCanonicalSpecExampleResolves(): void
    {
        $context = (new EvaluationContext())
            ->withExternalConstant('patient', ['resourceType' => 'Patient', 'id' => '123']);

        $template = 'Observation?code=http://loinc.org|65972-2&date=gt{{today()-7 days}}&subject={{%patient.id}}';
        $out      = $this->resolver->resolve($template, $context);

        self::assertMatchesRegularExpression(
            '#^Observation\?code=http://loinc\.org\|65972-2&date=gt\d{4}-\d{2}-\d{2}&subject=123$#',
            $out,
        );
    }

    public function testCodingResultBecomesToken(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('c', [
            'system'  => 'http://loinc.org',
            'code'    => '65972-2',
            'display' => 'X',
        ]);

        $out = $this->resolver->resolve('Observation?code={{%c}}', $context);

        self::assertSame('Observation?code=http://loinc.org|65972-2', $out);
    }

    public function testCodeableConceptResultBecomesCodingTokens(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('cc', [
            'coding' => [
                ['system' => 'http://loinc.org', 'code' => '1234-5'],
                ['system' => 'http://snomed.info/sct', 'code' => '99999'],
            ],
            'text' => 'x',
        ]);

        $out = $this->resolver->resolve('Condition?code={{%cc}}', $context);

        self::assertSame('Condition?code=http://loinc.org|1234-5,http://snomed.info/sct|99999', $out);
    }

    public function testQuantityResultBecomesQuantityToken(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('q', [
            'value'  => 5,
            'unit'   => 'mg',
            'system' => 'http://unitsofmeasure.org',
            'code'   => 'mg',
        ]);

        $out = $this->resolver->resolve('Observation?value-quantity={{%q}}', $context);

        self::assertSame('Observation?value-quantity=5|http://unitsofmeasure.org|mg', $out);
    }

    public function testIdentifierResultBecomesToken(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('id', [
            'system' => 'urn:oid:1.2.3',
            'value'  => 'ABC 123',
        ]);

        $out = $this->resolver->resolve('Patient?identifier={{%id}}', $context);

        // The code/value leaf is percent-encoded (space → %20); the system and the `|` separator are raw.
        self::assertSame('Patient?identifier=urn:oid:1.2.3|ABC%20123', $out);
    }

    public function testCodingWithCodeButNoSystemHasNoLeadingPipe(): void
    {
        // FHIR token search: `code` matches any system; `|code` would mean "no system" (matches ~nothing).
        $context = (new EvaluationContext())->withExternalConstant('c', ['code' => '65972-2']);

        $out = $this->resolver->resolve('Observation?code={{%c}}', $context);

        self::assertSame('Observation?code=65972-2', $out);
    }

    public function testCodingWithSystemButNoCodeKeepsTrailingPipe(): void
    {
        // `system|` means "any code in that system" — the trailing pipe is significant.
        $context = (new EvaluationContext())->withExternalConstant('c', ['system' => 'http://loinc.org']);

        $out = $this->resolver->resolve('Observation?code={{%c}}', $context);

        self::assertSame('Observation?code=http://loinc.org|', $out);
    }

    public function testQuantityBareValueHasNoTrailingSeparators(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('q', ['value' => 5]);

        $out = $this->resolver->resolve('Observation?value-quantity={{%q}}', $context);

        self::assertSame('Observation?value-quantity=5', $out);
    }

    public function testQuantityWithCodeButNoSystemKeepsInternalEmpty(): void
    {
        $context = (new EvaluationContext())->withExternalConstant('q', ['value' => 5, 'unit' => 'mg', 'code' => 'mg']);

        $out = $this->resolver->resolve('Observation?value-quantity={{%q}}', $context);

        self::assertSame('Observation?value-quantity=5||mg', $out);
    }

    public function testAllParametersDroppedOmitsTheQuestionMark(): void
    {
        // Every param resolves empty → no `?` at all, rather than the fetch-everything `Observation?`.
        $context = (new EvaluationContext())->withExternalConstant('patient', ['resourceType' => 'Patient']);

        $out = $this->resolver->resolve('Observation?subject={{%patient.id}}', $context);

        self::assertSame('Observation', $out);
    }

    public function testEmptyHoleDropsTheWholeParameter(): void
    {
        // %patient has no id → {{%patient.id}} is empty → the subject parameter is dropped entirely,
        // leaving the remaining parameters. NOTE: this widens the search (see resolver docblock / M04).
        $context = (new EvaluationContext())
            ->withExternalConstant('patient', ['resourceType' => 'Patient']);

        $out = $this->resolver->resolve('Observation?subject={{%patient.id}}&status=final', $context);

        self::assertSame('Observation?status=final', $out);
    }

    public function testUnterminatedHoleThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->resolver->resolve('Observation?subject={{%patient.id', new EvaluationContext());
    }
}
