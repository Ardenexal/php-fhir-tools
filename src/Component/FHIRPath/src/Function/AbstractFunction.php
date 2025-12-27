<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Abstract base class for FHIRPath functions.
 *
 * Provides common functionality for parameter validation and utilities.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
abstract class AbstractFunction implements FunctionInterface
{
    /**
     * @param string $name The function name
     */
    public function __construct(
        private readonly string $name
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Validate the number of parameters.
     *
     * @param array<int, mixed> $parameters The parameters to validate
     * @param int               $expected   The expected number of parameters
     * @param int|null          $max        The maximum number of parameters (if different from expected)
     *
     * @throws EvaluationException If parameter count is invalid
     */
    protected function validateParameterCount(array $parameters, int $expected, ?int $max = null): void
    {
        $count = count($parameters);
        $max   = $max ?? $expected;

        if ($count < $expected || $count > $max) {
            if ($expected === $max) {
                throw new EvaluationException(sprintf('Function %s() expects exactly %d parameter(s), %d given', $this->name, $expected, $count), 0, 0);
            }

            throw new EvaluationException(sprintf('Function %s() expects %d-%d parameter(s), %d given', $this->name, $expected, $max, $count), 0, 0);
        }
    }

    /**
     * Validate minimum parameter count.
     *
     * @param array<int, mixed> $parameters The parameters to validate
     * @param int               $min        The minimum number of parameters
     *
     * @throws EvaluationException If parameter count is too low
     */
    protected function validateMinParameters(array $parameters, int $min): void
    {
        $count = count($parameters);

        if ($count < $min) {
            throw new EvaluationException(sprintf('Function %s() expects at least %d parameter(s), %d given', $this->name, $min, $count), 0, 0);
        }
    }
}
