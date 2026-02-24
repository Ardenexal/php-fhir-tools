<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * trace(name: String[, projection: expression]): collection
 *
 * Logs diagnostic information about the current collection and returns the
 * input collection unchanged. Useful for debugging complex FHIRPath expressions.
 *
 * - `name`       identifies the trace point in the log output.
 * - `projection` (optional) transforms items before logging; the original
 *                input is still returned regardless.
 *
 * Output is written via a PSR-3 logger (debug level) if one has been set on
 * the evaluator via `FHIRPathEvaluator::setLogger()`, otherwise falls back to
 * PHP's `error_log()`.
 *
 * Spec reference: FHIRPath §5.9
 *
 * @author FHIR Tools Contributors
 */
final class TraceFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('trace');
    }

    /**
     * Execute the trace function for debugging.
     *
     * Logs diagnostic information about the collection (optionally transformed by a projection)
     * and returns the original input unchanged. Useful for debugging FHIRPath expressions.
     *
     * @param Collection        $input      The input collection (returned unchanged)
     * @param array<int, mixed> $parameters [0] = name label expression, [1] = optional projection expression
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection The original input collection, unchanged
     *
     * @throws EvaluationException If evaluator is not set in context
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 1 parameter (name) or 2 parameters (name + projection expression)
        $this->validateParameterCount($parameters, 1, 2);
        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not set in context', 0, 0);
        }

        // Resolve the name parameter
        $nameResult = $evaluator->evaluateWithContext($parameters[0], $context);
        $name       = $nameResult->isEmpty() ? '(unnamed)' : (string) $nameResult->first();

        // Determine what to log — the projected values if a projection is given, else input as-is
        if (count($parameters) === 2 && $parameters[1] instanceof ExpressionNode) {
            $logItems = [];
            foreach ($input as $index => $item) {
                $itemContext = $context->withIterationVariables($item, $index, $input->count());
                $projected   = $evaluator->evaluateWithContext($parameters[1], $itemContext);
                foreach ($projected as $value) {
                    $logItems[] = $value;
                }
            }
        } else {
            $logItems = $input->toArray();
        }

        $message = sprintf(
            'trace[%s]: [%s] (%d item%s)',
            $name,
            implode(', ', array_map($this->formatValue(...), $logItems)),
            count($logItems),
            count($logItems) === 1 ? '' : 's',
        );

        $logger = $evaluator->getLogger();
        if ($logger !== null) {
            $logger->debug($message);
        } else {
            error_log($message);
        }

        // Always return the original input unchanged
        return $input;
    }

    /**
     * Format a value for display in the trace output.
     *
     * Converts values to human-readable strings for logging:
     *   - null → 'null'
     *   - true → 'true', false → 'false'
     *   - Scalars (strings, numbers) → string representation
     *   - Arrays → '{array}'
     *   - Objects → '{ClassName}'
     *
     * @param mixed $value The value to format
     *
     * @return string The formatted string representation
     */
    private function formatValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        // Scalars include: string, int, float
        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_array($value)) {
            return '{array}';
        }

        // For objects, show the class name
        return '{' . get_class($value) . '}';
    }
}
