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

    /**
     * Return the direct child values of a node.
     *
     * Mirrors the same unwrapping rules as the evaluator's navigateProperty/wrapValue:
     * - Associative array or object: each property value; list-valued properties are
     *   flattened so each element becomes its own child (consistent with collection semantics).
     * - List array: each element is a direct child.
     * - Scalars / null: no children.
     *
     * Used by children() and descendants().
     *
     * @return array<int, mixed>
     */
    protected function getNodeChildren(mixed $node): array
    {
        if ($node === null || is_scalar($node)) {
            return [];
        }

        /** @var array<int, mixed> $rawValues */
        $rawValues = [];

        if (is_array($node)) {
            if (array_is_list($node)) {
                // Each element of the list is a direct child
                $rawValues = $node;
            } else {
                // Associative array — children are all property values
                $rawValues = array_values($node);
            }
        } elseif (is_object($node)) {
            // Public object properties (no CodeGeneration dependency needed)
            $rawValues = array_values(get_object_vars($node));
        }

        $children = [];
        foreach ($rawValues as $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value) && array_is_list($value)) {
                // List-valued properties are unwrapped: each element is a child
                foreach ($value as $item) {
                    if ($item !== null) {
                        $children[] = $item;
                    }
                }
            } else {
                $children[] = $value;
            }
        }

        return $children;
    }
}
