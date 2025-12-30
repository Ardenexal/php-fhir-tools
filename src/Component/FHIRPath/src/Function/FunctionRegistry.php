<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Registry for FHIRPath functions.
 *
 * Manages registration and lookup of all available FHIRPath functions.
 * Uses singleton pattern to ensure single registry instance.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class FunctionRegistry
{
    private static ?self $instance = null;

    /**
     * @var array<string, FunctionInterface>
     */
    private array $functions = [];

    private function __construct()
    {
        // Private constructor for singleton
        $this->registerBuiltInFunctions();
    }

    /**
     * Register all built-in FHIRPath functions.
     */
    private function registerBuiltInFunctions(): void
    {
        // Existence functions
        $this->registerSafe(new EmptyFunction());
        $this->registerSafe(new ExistsFunction());
        $this->registerSafe(new AllFunction());
        $this->registerSafe(new CountFunction());
        $this->registerSafe(new AllTrueFunction());
        $this->registerSafe(new AnyTrueFunction());
        $this->registerSafe(new AllFalseFunction());
        $this->registerSafe(new AnyFalseFunction());

        // Filtering functions
        $this->registerSafe(new WhereFunction());
        $this->registerSafe(new SelectFunction());
        $this->registerSafe(new FirstFunction());
        $this->registerSafe(new LastFunction());
        $this->registerSafe(new TailFunction());
        $this->registerSafe(new TakeFunction());
        $this->registerSafe(new SkipFunction());
        $this->registerSafe(new SingleFunction());
        $this->registerSafe(new DistinctFunction());

        // Subsetting functions
        $this->registerSafe(new UnionFunction());
        $this->registerSafe(new IntersectFunction());
        $this->registerSafe(new ExcludeFunction());

        // String functions
        $this->registerSafe(new SubstringFunction());
        $this->registerSafe(new LengthFunction());
        $this->registerSafe(new StartsWithFunction());
        $this->registerSafe(new EndsWithFunction());
        $this->registerSafe(new ContainsStringFunction());
        $this->registerSafe(new IndexOfFunction());
        $this->registerSafe(new UpperFunction());
        $this->registerSafe(new LowerFunction());
        $this->registerSafe(new ReplaceFunction());
        $this->registerSafe(new MatchesFunction());
        $this->registerSafe(new TrimFunction());
        $this->registerSafe(new SplitFunction());

        // Math functions
        $this->registerSafe(new SumFunction());
        $this->registerSafe(new AbsFunction());
        $this->registerSafe(new CeilingFunction());
        $this->registerSafe(new FloorFunction());
        $this->registerSafe(new TruncateFunction());
        $this->registerSafe(new RoundFunction());
        $this->registerSafe(new ExpFunction());
        $this->registerSafe(new LnFunction());
        $this->registerSafe(new LogFunction());
        $this->registerSafe(new PowerFunction());
        $this->registerSafe(new SqrtFunction());
        $this->registerSafe(new MinFunction());
        $this->registerSafe(new MaxFunction());
        $this->registerSafe(new AvgFunction());

        // Date/Time functions
        $this->registerSafe(new NowFunction());
        $this->registerSafe(new TimeOfDayFunction());
        $this->registerSafe(new TodayFunction());
        $this->registerSafe(new ToMillisecondsFunction());
        $this->registerSafe(new ToSecondsFunction());

        // Type functions
        $this->registerSafe(new OfTypeFunction());
        $this->registerSafe(new HasValueFunction());

        // Combining functions
        $this->registerSafe(new CombineFunction());
        $this->registerSafe(new IifFunction());
    }

    /**
     * Register a function without throwing if it already exists.
     */
    private function registerSafe(FunctionInterface $function): void
    {
        $name = $function->getName();
        if (!isset($this->functions[$name])) {
            $this->functions[$name] = $function;
        }
    }

    /**
     * Get the singleton registry instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register a function.
     *
     * @param FunctionInterface $function The function to register
     *
     * @throws EvaluationException If function with same name already registered
     */
    public function register(FunctionInterface $function): void
    {
        $name = $function->getName();

        if (isset($this->functions[$name])) {
            throw new EvaluationException(sprintf('Function "%s" is already registered', $name), 0, 0);
        }

        $this->functions[$name] = $function;
    }

    /**
     * Get a function by name.
     *
     * @param string $name The function name
     *
     * @return FunctionInterface The function
     *
     * @throws EvaluationException If function not found
     */
    public function get(string $name): FunctionInterface
    {
        if (!isset($this->functions[$name])) {
            throw new EvaluationException(sprintf('Unknown function "%s"', $name), 0, 0);
        }

        return $this->functions[$name];
    }

    /**
     * Check if a function is registered.
     *
     * @param string $name The function name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->functions[$name]);
    }

    /**
     * Get all registered function names.
     *
     * @return array<int, string>
     */
    public function getFunctionNames(): array
    {
        return array_keys($this->functions);
    }

    /**
     * Clear all registered functions (for testing).
     *
     * @internal
     */
    public function clear(): void
    {
        $this->functions = [];
    }
}
