<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext
 */
final class EvaluationContextTest extends TestCase
{
    public function testDefaultContext(): void
    {
        $context = new EvaluationContext();

        self::assertNull($context->getRootResource());
        self::assertNull($context->getCurrentNode());
    }

    public function testSetAndGetRootResource(): void
    {
        $context  = new EvaluationContext();
        $resource = ['resourceType' => 'Patient'];

        $context->setRootResource($resource);

        self::assertSame($resource, $context->getRootResource());
    }

    public function testWithCurrentNode(): void
    {
        $context = new EvaluationContext();
        $node    = ['name' => 'Test'];

        $newContext = $context->withCurrentNode($node);

        self::assertNotSame($context, $newContext);
        self::assertNull($context->getCurrentNode());
        self::assertSame($node, $newContext->getCurrentNode());
    }

    public function testVariables(): void
    {
        $context = new EvaluationContext();

        self::assertFalse($context->hasVariable('test'));
        self::assertNull($context->getVariable('test'));

        $context->setVariable('test', 'value');

        self::assertTrue($context->hasVariable('test'));
        self::assertSame('value', $context->getVariable('test'));
    }

    public function testWithVariable(): void
    {
        $context    = new EvaluationContext();
        $newContext = $context->withVariable('test', 'value');

        self::assertNotSame($context, $newContext);
        self::assertFalse($context->hasVariable('test'));
        self::assertTrue($newContext->hasVariable('test'));
        self::assertSame('value', $newContext->getVariable('test'));
    }

    public function testExternalConstants(): void
    {
        $context = new EvaluationContext();

        self::assertFalse($context->hasExternalConstant('ucum'));
        self::assertNull($context->getExternalConstant('ucum'));

        $context->setExternalConstant('ucum', 'http://unitsofmeasure.org');

        self::assertTrue($context->hasExternalConstant('ucum'));
        self::assertSame('http://unitsofmeasure.org', $context->getExternalConstant('ucum'));
    }

    public function testWithExternalConstant(): void
    {
        $context    = new EvaluationContext();
        $newContext = $context->withExternalConstant('ucum', 'http://unitsofmeasure.org');

        self::assertNotSame($context, $newContext);
        self::assertFalse($context->hasExternalConstant('ucum'));
        self::assertTrue($newContext->hasExternalConstant('ucum'));
    }

    public function testWithIterationVariables(): void
    {
        $context = new EvaluationContext();
        $item    = ['name' => 'Test'];

        $newContext = $context->withIterationVariables($item, 0, 5);

        self::assertNotSame($context, $newContext);
        self::assertSame($item, $newContext->getCurrentNode());
        self::assertSame($item, $newContext->getVariable('this'));
        self::assertSame(0, $newContext->getVariable('index'));
        self::assertSame(5, $newContext->getVariable('total'));
    }
}
