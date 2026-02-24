<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

/**
 * Tests for the trace() FHIRPath function.
 *
 * @author FHIR Tools Contributors
 */
final class TraceFunctionTest extends TestCase
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

    public function testTraceReturnsInputUnchanged(): void
    {
        $resource = ['items' => [1, 2, 3]];
        $result   = $this->evaluate("items.trace('test')", $resource);

        self::assertSame(3, $result->count());
        self::assertSame([1, 2, 3], $result->toArray());
    }

    public function testTraceOnEmptyCollectionReturnsEmpty(): void
    {
        $result = $this->evaluate("{}.trace('empty')", null);

        self::assertTrue($result->isEmpty());
    }

    public function testTraceOnScalarReturnsScalar(): void
    {
        $result = $this->evaluate("(42).trace('num')", null);

        self::assertTrue($result->isSingle());
        self::assertSame(42, $result->first());
    }

    public function testTraceWithProjectionStillReturnsOriginalInput(): void
    {
        // trace(name, projection) logs the projected values but returns the original items
        $resource = ['name' => [['family' => 'Smith'], ['family' => 'Jones']]];
        $result   = $this->evaluate("name.trace('families', family)", $resource);

        // Returns the full HumanName objects, not the projected family strings
        self::assertSame(2, $result->count());
        self::assertSame(['family' => 'Smith'], $result->first());
    }

    public function testTraceWritesToPsrLogger(): void
    {
        $logged = [];
        $logger = new class ($logged) extends AbstractLogger {
            /** @param array<int, string> $log */
            public function __construct(private array &$log)
            {
            }

            public function log(mixed $level, \Stringable|string $message, array $context = []): void
            {
                $this->log[] = (string) $message;
            }
        };

        $this->evaluator->setLogger($logger);

        $resource = ['value' => 42];
        $this->evaluate("value.trace('myTrace')", $resource);

        self::assertCount(1, $logged);
        self::assertStringContainsString('myTrace', $logged[0]);
        self::assertStringContainsString('42', $logged[0]);
    }

    public function testTraceProjectionReceivesIndexVariable(): void
    {
        // $index should be available inside the projection expression
        $logged = [];
        $logger = new class ($logged) extends AbstractLogger {
            /** @param array<int, string> $log */
            public function __construct(private array &$log)
            {
            }

            public function log(mixed $level, \Stringable|string $message, array $context = []): void
            {
                $this->log[] = (string) $message;
            }
        };

        $this->evaluator->setLogger($logger);

        $resource = ['items' => ['a', 'b', 'c']];
        $this->evaluate("items.trace('indexed', \$index)", $resource);

        // Logged projection values should be the indices: 0, 1, 2
        self::assertCount(1, $logged);
        self::assertStringContainsString('0', $logged[0]);
        self::assertStringContainsString('1', $logged[0]);
        self::assertStringContainsString('2', $logged[0]);
    }

    public function testTraceCanChainWithOtherOperations(): void
    {
        // trace() is transparent — chaining should work exactly as without it
        $resource = ['items' => [10, 20, 30]];

        $withTrace    = $this->evaluate("items.trace('debug').count()", $resource);
        $withoutTrace = $this->evaluate('items.count()', $resource);

        self::assertSame($withoutTrace->first(), $withTrace->first());
    }
}
