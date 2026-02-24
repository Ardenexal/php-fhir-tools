<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\TypeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\TypeInfo;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\BooleanPrimitive;
use PHPUnit\Framework\TestCase;

class TypeFunctionTest extends TestCase
{
    public function testTypeOnPhpBoolean(): void
    {
        $typeFunc = new TypeFunction();
        $input    = Collection::single(true);
        $context  = new EvaluationContext();

        $result = $typeFunc->execute($input, [], $context);

        self::assertCount(1, $result);
        $typeInfo = $result->first();
        self::assertInstanceOf(TypeInfo::class, $typeInfo);
        self::assertSame('System', $typeInfo->namespace);
        self::assertSame('Boolean', $typeInfo->name);
    }

    public function testTypeOnFhirBooleanPrimitive(): void
    {
        $typeFunc = new TypeFunction();
        $fhirBool = new BooleanPrimitive(value: true);
        $input    = Collection::single($fhirBool);
        $context  = new EvaluationContext();

        $result = $typeFunc->execute($input, [], $context);

        self::assertCount(1, $result);
        $typeInfo = $result->first();
        self::assertInstanceOf(TypeInfo::class, $typeInfo);
        self::assertSame('FHIR', $typeInfo->namespace);
        self::assertSame('boolean', $typeInfo->name);
    }

    public function testTypeOnPhpString(): void
    {
        $typeFunc = new TypeFunction();
        $input    = Collection::single('hello');
        $context  = new EvaluationContext();

        $result = $typeFunc->execute($input, [], $context);

        self::assertCount(1, $result);
        $typeInfo = $result->first();
        self::assertInstanceOf(TypeInfo::class, $typeInfo);
        self::assertSame('System', $typeInfo->namespace);
        self::assertSame('String', $typeInfo->name);
    }

    public function testTypeOnInteger(): void
    {
        $typeFunc = new TypeFunction();
        $input    = Collection::single(42);
        $context  = new EvaluationContext();

        $result = $typeFunc->execute($input, [], $context);

        self::assertCount(1, $result);
        $typeInfo = $result->first();
        self::assertInstanceOf(TypeInfo::class, $typeInfo);
        self::assertSame('System', $typeInfo->namespace);
        self::assertSame('Integer', $typeInfo->name);
    }
}
