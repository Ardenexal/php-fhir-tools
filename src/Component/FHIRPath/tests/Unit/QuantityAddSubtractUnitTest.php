<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * B9 regression: quantity +/- must respect units. Previously add/sub applied the operation with the
 * left unit and NO conversion, fabricating wrong answers: `1 'mg' + 1 'cm' → 2 'mg'` and even
 * `1 'm' + 1 'cm' → 2 'm'` (compatible units not converted). Correct behaviour: incompatible units
 * yield empty (FHIRPath spec); compatible units are converted into the left operand's unit first.
 */
final class QuantityAddSubtractUnitTest extends TestCase
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression): Collection
    {
        return $this->evaluator->evaluate($this->parser->parse($this->lexer->tokenize($expression)), null);
    }

    public function testIncompatibleUnitsAdditionReturnsEmpty(): void
    {
        $result = $this->evaluate("1 'mg' + 1 'cm'");

        self::assertTrue($result->isEmpty(), 'mg + cm is dimensionally incompatible → empty');
    }

    public function testCompatibleUnitsAdditionConvertsToLeftUnit(): void
    {
        $result = $this->evaluate("1 'm' + 1 'cm'");

        self::assertFalse($result->isEmpty());
        $q = $result->first();
        self::assertIsArray($q);
        self::assertEqualsWithDelta(1.01, $q['value'], 1e-9, '1 m + 1 cm = 1.01 m');
        self::assertSame('m', $q['code']);
    }

    public function testSameUnitAdditionOperatesDirectly(): void
    {
        $result = $this->evaluate("1 'mg' + 1 'mg'");

        self::assertFalse($result->isEmpty());
        $q = $result->first();
        self::assertIsArray($q);
        self::assertEqualsWithDelta(2.0, $q['value'], 1e-9);
        self::assertSame('mg', $q['code']);
    }

    public function testCompatibleUnitsSubtractionConvertsToLeftUnit(): void
    {
        $result = $this->evaluate("2 'm' - 50 'cm'");

        self::assertFalse($result->isEmpty());
        $q = $result->first();
        self::assertIsArray($q);
        self::assertEqualsWithDelta(1.5, $q['value'], 1e-9, '2 m - 50 cm = 1.5 m');
        self::assertSame('m', $q['code']);
    }
}
