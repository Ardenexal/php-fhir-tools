<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\FHIRPath\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\ComparisonService;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use PHPUnit\Framework\TestCase;

final class QuantityObjectTest extends TestCase
{
    private ComparisonService $service;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new FHIRPathEvaluator();
        $this->service   = new ComparisonService($this->evaluator);
    }

    public function testQuantityObjectExtraction(): void
    {
        // This is what a deserialized Quantity looks like from FHIR JSON
        $quantity = new Quantity(
            value: 185.0,
            code: new CodePrimitive(value: '[lb_av]'),
            unit: 'lbs',
            system: new UriPrimitive(value: 'http://unitsofmeasure.org'),
        );

        // Test that normalizeValue unwraps the primitives
        $codeValue   = $this->evaluator->normalizeValue($quantity->code);
        $systemValue = $this->evaluator->normalizeValue($quantity->system);

        self::assertSame('[lb_av]', $codeValue);
        self::assertSame('http://unitsofmeasure.org', $systemValue);
    }

    public function testQuantityObjectComparison(): void
    {
        // Observation.value from deserialized resource
        $quantity = new Quantity(
            value: 185.0,
            code: new CodePrimitive(value: '[lb_av]'),
            unit: 'lbs',
            system: new UriPrimitive(value: 'http://unitsofmeasure.org'),
        );

        // FHIRPath literal: 185 '[lb_av]'
        $literal = [
            'value'  => 185.0,
            'code'   => '[lb_av]',
            'unit'   => '[lb_av]',
            'system' => 'http://unitsofmeasure.org',
        ];

        $left  = Collection::single($quantity);
        $right = Collection::single($literal);

        $result = $this->service->compareEquality($left, $right, '~');

        self::assertTrue($result->isSingle(), 'Expected single result');
        self::assertTrue($result->first(), 'Expected equivalence to be true');
    }

    public function testQuantityObjectOrdering(): void
    {
        // Observation.value = 185 '[lb_av]'
        $quantity = new Quantity(
            value: 185.0,
            code: new CodePrimitive(value: '[lb_av]'),
            unit: 'lbs',
            system: new UriPrimitive(value: 'http://unitsofmeasure.org'),
        );

        // FHIRPath literal: 200 '[lb_av]'
        $literal = [
            'value'  => 200.0,
            'code'   => '[lb_av]',
            'unit'   => '[lb_av]',
            'system' => 'http://unitsofmeasure.org',
        ];

        $left  = Collection::single($quantity);
        $right = Collection::single($literal);

        $result = $this->service->compareOrdering($left, $right, fn ($a, $b) => $a < $b);

        self::assertTrue($result->isSingle(), 'Expected single result');
        self::assertTrue($result->first(), 'Expected 185 < 200 to be true');
    }

    public function testQuantityObjectWithoutSystem(): void
    {
        // Test quantity without explicit system (should default to UCUM)
        $quantity = new Quantity(
            value: 185.0,
            code: new CodePrimitive(value: '[lb_av]'),
            unit: 'lbs',
        );

        // FHIRPath literal: 185 '[lb_av]'
        $literal = [
            'value'  => 185.0,
            'code'   => '[lb_av]',
            'unit'   => '[lb_av]',
            'system' => null,
        ];

        $left  = Collection::single($quantity);
        $right = Collection::single($literal);

        $result = $this->service->compareEquality($left, $right, '~');

        self::assertTrue($result->isSingle(), 'Expected single result');
        self::assertTrue($result->first(), 'Expected equivalence to be true');
    }
}
