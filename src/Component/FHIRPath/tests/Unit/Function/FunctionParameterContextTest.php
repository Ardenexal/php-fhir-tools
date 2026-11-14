<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Function\AbstractFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\ComparableFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\DecodeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\EncodeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\EndsWithFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\EscapeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\ExcludeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\HighBoundaryFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\JoinFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\LogFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\LowBoundaryFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\MatchesFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\MatchesFullFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\PowerFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\RoundFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\SkipFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\SplitFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\StartsWithFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\TakeFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Functions that evaluate their own arguments must do so in the caller's evaluation context and
 * leave that context as they found it.
 *
 * Evaluating an argument used to replace the evaluator's context with a fresh one whose focus was
 * the context object itself. The function's own result was right, but everything evaluated after it
 * in the same expression navigated from the wrong focus and lost `%resource` and other variables.
 */
#[CoversClass(AbstractFunction::class)]
#[CoversClass(ComparableFunction::class)]
#[CoversClass(DecodeFunction::class)]
#[CoversClass(EncodeFunction::class)]
#[CoversClass(EndsWithFunction::class)]
#[CoversClass(EscapeFunction::class)]
#[CoversClass(ExcludeFunction::class)]
#[CoversClass(HighBoundaryFunction::class)]
#[CoversClass(JoinFunction::class)]
#[CoversClass(LogFunction::class)]
#[CoversClass(LowBoundaryFunction::class)]
#[CoversClass(MatchesFunction::class)]
#[CoversClass(MatchesFullFunction::class)]
#[CoversClass(PowerFunction::class)]
#[CoversClass(RoundFunction::class)]
#[CoversClass(SkipFunction::class)]
#[CoversClass(SplitFunction::class)]
#[CoversClass(StartsWithFunction::class)]
#[CoversClass(TakeFunction::class)]
final class FunctionParameterContextTest extends TestCase
{
    /**
     * @var array<string, mixed>
     */
    private const array PATIENT = [
        'resourceType' => 'Patient',
        'active'       => true,
        'name'         => [['given' => ['Ann', 'Bo']]],
    ];

    private FHIRPathService $service;

    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }

    /**
     * Each expression calls one argument-evaluating function bare (on the select() item, not through
     * `.`) and then reads `$this`, which only works while the evaluator still has the caller's context.
     *
     * @return iterable<string, array{string}>
     */
    public static function bareCallThenThisCases(): iterable
    {
        yield 'matches' => ["'abc'.select(matches('b') and \$this = 'abc')"];
        yield 'matchesFull' => ["'abc'.select(matchesFull('abc') and \$this = 'abc')"];
        yield 'startsWith' => ["'abc'.select(startsWith('a') and \$this = 'abc')"];
        yield 'endsWith' => ["'abc'.select(endsWith('c') and \$this = 'abc')"];
        yield 'take' => ["'abc'.select(take(1).count() = 1 and \$this = 'abc')"];
        yield 'skip' => ["'abc'.select(skip(1).empty() and \$this = 'abc')"];
        yield 'join' => ["'abc'.select(join(',') = 'abc' and \$this = 'abc')"];
        yield 'split' => ["'a,b'.select(split(',').count() = 2 and \$this = 'a,b')"];
        yield 'encode' => ["'abc'.select(encode('base64') = 'YWJj' and \$this = 'abc')"];
        yield 'decode' => ["'YWJj'.select(decode('base64') = 'abc' and \$this = 'YWJj')"];
        yield 'escape' => ["'<a>'.select(escape('html') = '&lt;a&gt;' and \$this = '<a>')"];
        yield 'exclude' => ["'abc'.select(exclude('x').count() = 1 and \$this = 'abc')"];
        yield 'comparable' => ['1.select(comparable(2) and $this = 1)'];
        yield 'highBoundary' => ['1.0.select(highBoundary(2).exists() and $this = 1.0)'];
        yield 'lowBoundary' => ['1.0.select(lowBoundary(2).exists() and $this = 1.0)'];
        yield 'power' => ['2.select(power(3) = 8 and $this = 2)'];
        yield 'round' => ['1.5.select(round(0) = 2 and $this = 1.5)'];
        yield 'log' => ['100.select(log(10) = 2 and $this = 100)'];
    }

    #[DataProvider('bareCallThenThisCases')]
    public function testExpressionAfterABareCallStillSeesTheCallersContext(string $expression): void
    {
        self::assertSame([true], $this->service->evaluate($expression, self::PATIENT)->toArray());
    }

    public function testASecondBareFunctionCallSeesTheSameFocus(): void
    {
        // The case that exposed the bug: the second matches() ran against the context object.
        self::assertSame([true], $this->service->evaluate("'a'.select(matches('x') = matches('y'))", self::PATIENT)->toArray());
    }

    /**
     * Arguments that navigate or read `%resource` must resolve against the caller's context.
     *
     * @return iterable<string, array{string, list<mixed>}>
     */
    public static function argumentNavigationCases(): iterable
    {
        yield 'matches reads %resource' => ['name.given.first().matches(%resource.name.given.last())', [false]];
        yield 'endsWith reads %resource' => ["'xAnn'.endsWith(%resource.name.given.first())", [true]];
        yield 'take reads %resource' => ['name.given.take(%resource.name.given.count() - 1)', ['Ann']];
        yield 'join reads %resource' => ['name.given.join(%resource.name.given.first())', ['AnnAnnBo']];
        yield 'exclude reads %resource' => ['name.given.exclude(%resource.name.given.first())', ['Bo']];
    }

    /**
     * @param list<mixed> $expected
     */
    #[DataProvider('argumentNavigationCases')]
    public function testArgumentsResolveAgainstTheCallersContext(string $expression, array $expected): void
    {
        self::assertSame($expected, $this->service->evaluate($expression, self::PATIENT)->toArray());
    }
}
