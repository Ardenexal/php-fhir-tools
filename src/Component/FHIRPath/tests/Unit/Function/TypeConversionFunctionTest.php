<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * Tests for type conversion functions:
 *   convertsToInteger(), convertsToDecimal(),
 *   toDate(), convertsToDate(),
 *   toDateTime(), convertsToDateTime(),
 *   toTime(), convertsToTime()
 *
 * @author FHIR Tools Contributors
 */
final class TypeConversionFunctionTest extends TestCase
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

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // convertsToInteger()
    // -------------------------------------------------------------------------

    public function testConvertsToIntegerTrueForInteger(): void
    {
        self::assertTrue($this->evaluate('42.convertsToInteger()', null)->first());
    }

    public function testConvertsToIntegerTrueForNumericString(): void
    {
        self::assertTrue($this->evaluate("'7'.convertsToInteger()", null)->first());
    }

    public function testConvertsToIntegerTrueForBoolean(): void
    {
        self::assertTrue($this->evaluate('true.convertsToInteger()', null)->first());
    }

    public function testConvertsToIntegerFalseForDecimalWithFraction(): void
    {
        $resource = ['val' => 3.5];
        self::assertFalse($this->evaluate('val.convertsToInteger()', $resource)->first());
    }

    public function testConvertsToIntegerFalseForString(): void
    {
        self::assertFalse($this->evaluate("'hello'.convertsToInteger()", null)->first());
    }

    public function testConvertsToIntegerEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToInteger()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToDecimal()
    // -------------------------------------------------------------------------

    public function testConvertsToDecimalTrueForNumeric(): void
    {
        self::assertTrue($this->evaluate('3.convertsToDecimal()', null)->first());
        self::assertTrue($this->evaluate("'3.14'.convertsToDecimal()", null)->first());
        self::assertTrue($this->evaluate('true.convertsToDecimal()', null)->first());
    }

    public function testConvertsToDecimalFalseForNonNumeric(): void
    {
        self::assertFalse($this->evaluate("'abc'.convertsToDecimal()", null)->first());
    }

    public function testConvertsToDecimalEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToDecimal()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // toDate()
    // -------------------------------------------------------------------------

    public function testToDatePassthroughYear(): void
    {
        $resource = ['d' => '2020'];
        self::assertSame('2020', $this->evaluate('d.toDate()', $resource)->first());
    }

    public function testToDatePassthroughYearMonth(): void
    {
        $resource = ['d' => '2020-06'];
        self::assertSame('2020-06', $this->evaluate('d.toDate()', $resource)->first());
    }

    public function testToDatePassthroughFullDate(): void
    {
        $resource = ['d' => '2020-06-15'];
        self::assertSame('2020-06-15', $this->evaluate('d.toDate()', $resource)->first());
    }

    public function testToDateStripsTimeFromDateTime(): void
    {
        $resource = ['d' => '2020-06-15T10:30:00'];
        self::assertSame('2020-06-15', $this->evaluate('d.toDate()', $resource)->first());
    }

    public function testToDateStripsTimezoneDateTime(): void
    {
        $resource = ['d' => '2020-06-15T10:30:00+10:00'];
        self::assertSame('2020-06-15', $this->evaluate('d.toDate()', $resource)->first());
    }

    public function testToDateReturnsEmptyForInvalidString(): void
    {
        self::assertTrue($this->evaluate("'not-a-date'.toDate()", null)->isEmpty());
    }

    public function testToDateReturnsEmptyForNonString(): void
    {
        self::assertTrue($this->evaluate('42.toDate()', null)->isEmpty());
    }

    public function testToDateReturnsEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.toDate()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToDate()
    // -------------------------------------------------------------------------

    public function testConvertsToDateTrueForDateString(): void
    {
        $resource = ['d' => '2020-06-15'];
        self::assertTrue($this->evaluate('d.convertsToDate()', $resource)->first());
    }

    public function testConvertsToDateTrueForDateTimeString(): void
    {
        $resource = ['d' => '2020-06-15T10:30:00'];
        self::assertTrue($this->evaluate('d.convertsToDate()', $resource)->first());
    }

    public function testConvertsToDateFalseForInvalidString(): void
    {
        self::assertFalse($this->evaluate("'hello'.convertsToDate()", null)->first());
    }

    public function testConvertsToDateEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToDate()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // toDateTime()
    // -------------------------------------------------------------------------

    public function testToDateTimePassthroughPartialDate(): void
    {
        $resource = ['d' => '2020'];
        self::assertSame('2020', $this->evaluate('d.toDateTime()', $resource)->first());
    }

    public function testToDateTimePassthroughFullDateTime(): void
    {
        $resource = ['d' => '2020-06-15T10:30:00'];
        self::assertSame('2020-06-15T10:30:00', $this->evaluate('d.toDateTime()', $resource)->first());
    }

    public function testToDateTimeWithTimezone(): void
    {
        $resource = ['d' => '2020-06-15T10:30:00+10:00'];
        self::assertSame('2020-06-15T10:30:00+10:00', $this->evaluate('d.toDateTime()', $resource)->first());
    }

    public function testToDateTimeReturnsEmptyForInvalid(): void
    {
        self::assertTrue($this->evaluate("'not-a-date'.toDateTime()", null)->isEmpty());
    }

    public function testToDateTimeReturnsEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.toDateTime()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToDateTime()
    // -------------------------------------------------------------------------

    public function testConvertsToDateTimeTrueForDateString(): void
    {
        $resource = ['d' => '2020-01-15'];
        self::assertTrue($this->evaluate('d.convertsToDateTime()', $resource)->first());
    }

    public function testConvertsToDateTimeFalseForInvalid(): void
    {
        self::assertFalse($this->evaluate("'garbage'.convertsToDateTime()", null)->first());
    }

    public function testConvertsToDateTimeEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToDateTime()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // toTime()
    // -------------------------------------------------------------------------

    public function testToTimePassthroughHourOnly(): void
    {
        $resource = ['t' => 'T14'];
        self::assertSame('T14', $this->evaluate('t.toTime()', $resource)->first());
    }

    public function testToTimePassthroughHourMinute(): void
    {
        $resource = ['t' => 'T14:30'];
        self::assertSame('T14:30', $this->evaluate('t.toTime()', $resource)->first());
    }

    public function testToTimePassthroughFull(): void
    {
        $resource = ['t' => 'T14:30:00'];
        self::assertSame('T14:30:00', $this->evaluate('t.toTime()', $resource)->first());
    }

    public function testToTimePassthroughWithMilliseconds(): void
    {
        $resource = ['t' => 'T14:30:00.123'];
        self::assertSame('T14:30:00.123', $this->evaluate('t.toTime()', $resource)->first());
    }

    public function testToTimeReturnsEmptyForMissingTPrefix(): void
    {
        // Time strings must start with 'T'
        self::assertTrue($this->evaluate("'14:30:00'.toTime()", null)->isEmpty());
    }

    public function testToTimeReturnsEmptyForInvalid(): void
    {
        self::assertTrue($this->evaluate("'not-a-time'.toTime()", null)->isEmpty());
    }

    public function testToTimeReturnsEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.toTime()', null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // convertsToTime()
    // -------------------------------------------------------------------------

    public function testConvertsToTimeTrueForTimeString(): void
    {
        $resource = ['t' => 'T10:00:00'];
        self::assertTrue($this->evaluate('t.convertsToTime()', $resource)->first());
    }

    public function testConvertsToTimeFalseForNonTimeString(): void
    {
        self::assertFalse($this->evaluate("'10:00:00'.convertsToTime()", null)->first());
    }

    public function testConvertsToTimeEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate('{}.convertsToTime()', null)->isEmpty());
    }
}
