<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\FHIRPath\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use PHPUnit\Framework\TestCase;

final class PolymorphicPropertyTest extends TestCase
{
    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    public function testPolymorphicPropertyWithNonNullValue(): void
    {
        // Create a mock observation-like object with valueQuantity
        $observation = new class () {
            public ?Quantity $valueQuantity = null;

            public function __construct()
            {
                $this->valueQuantity = new Quantity(
                    value: 185.0,
                    code: new CodePrimitive(value: '[lb_av]'),
                    unit: 'lbs',
                    system: new UriPrimitive(value: 'http://unitsofmeasure.org'),
                );
            }
        };

        // Access polymorphic property: .value should find .valueQuantity
        $result = $this->service->evaluate('value', $observation, fhirVersion: 'R4');

        self::assertFalse($result->isEmpty(), 'Expected .value to find .valueQuantity');
        self::assertInstanceOf(Quantity::class, $result->first());
    }

    public function testPolymorphicPropertyWithNullValue(): void
    {
        // Create a mock observation-like object with null valueQuantity
        $observation = new class () {
            public ?Quantity $valueQuantity = null;
        };

        // Access polymorphic property: .value should find .valueQuantity even if null
        $result = $this->service->evaluate('value', $observation, fhirVersion: 'R4');

        // When a property is null, FHIRPath should return empty collection
        self::assertTrue($result->isEmpty(), 'Expected .value to return empty when valueQuantity is null');
    }

    public function testPolymorphicPropertyExists(): void
    {
        // Create a mock observation-like object with valueQuantity
        $observation = new class () {
            public ?Quantity $valueQuantity = null;

            public function __construct()
            {
                $this->valueQuantity = new Quantity(
                    value: 185.0,
                    code: new CodePrimitive(value: '[lb_av]'),
                    unit: 'lbs',
                    system: new UriPrimitive(value: 'http://unitsofmeasure.org'),
                );
            }
        };

        // .value.exists() should return true when valueQuantity is non-null
        $result = $this->service->evaluate('value.exists()', $observation, fhirVersion: 'R4');

        self::assertTrue($result->isSingle(), 'Expected single boolean result');
        self::assertTrue($result->first(), 'Expected .value.exists() to return true');
    }

    public function testDirectPropertyAccess(): void
    {
        // Test that accessing a regular (non-polymorphic) property works
        $quantity = new Quantity(
            value: 185.0,
            code: new CodePrimitive(value: '[lb_av]'),
        );

        $result = $this->service->evaluate('value', $quantity, fhirVersion: 'R4');

        self::assertTrue($result->isSingle(), 'Expected single result');
        self::assertSame(185.0, $result->first());
    }
}
