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
 * @author Copilot
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
            throw EvaluationException::create(
                sprintf('Function "%s" is already registered', $name),
                0,
                0
            );
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
            throw EvaluationException::create(
                sprintf('Unknown function "%s"', $name),
                0,
                0
            );
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
