<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionInterface;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FunctionRegistry.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class FunctionRegistryTest extends TestCase
{
    private FunctionRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = FunctionRegistry::getInstance();
        $this->registry->clear();
    }

    protected function tearDown(): void
    {
        // Reset the singleton so a fresh registry (with all built-ins) is
        // constructed for subsequent test suites running in the same process.
        FunctionRegistry::reset();
    }

    public function testGetInstanceReturnsSameInstance(): void
    {
        $instance1 = FunctionRegistry::getInstance();
        $instance2 = FunctionRegistry::getInstance();

        self::assertSame($instance1, $instance2);
    }

    public function testRegisterAndGetFunction(): void
    {
        $function = $this->createMockFunction('test');
        $this->registry->register($function);

        $retrieved = $this->registry->get('test');

        self::assertSame($function, $retrieved);
    }

    public function testRegisterDuplicateFunctionThrowsException(): void
    {
        $function1 = $this->createMockFunction('test');
        $function2 = $this->createMockFunction('test');

        $this->registry->register($function1);

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Function "test" is already registered');

        $this->registry->register($function2);
    }

    public function testGetNonExistentFunctionThrowsException(): void
    {
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Unknown function "nonexistent"');

        $this->registry->get('nonexistent');
    }

    public function testHasFunction(): void
    {
        $function = $this->createMockFunction('test');

        self::assertFalse($this->registry->has('test'));

        $this->registry->register($function);

        self::assertTrue($this->registry->has('test'));
    }

    public function testGetFunctionNames(): void
    {
        $function1 = $this->createMockFunction('test1');
        $function2 = $this->createMockFunction('test2');

        $this->registry->register($function1);
        $this->registry->register($function2);

        $names = $this->registry->getFunctionNames();

        self::assertCount(2, $names);
        self::assertContains('test1', $names);
        self::assertContains('test2', $names);
    }

    private function createMockFunction(string $name): FunctionInterface
    {
        return new class ($name) implements FunctionInterface {
            public function __construct(private readonly string $name)
            {
            }

            public function getName(): string
            {
                return $this->name;
            }

            public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
            {
                return Collection::empty();
            }
        };
    }
}
