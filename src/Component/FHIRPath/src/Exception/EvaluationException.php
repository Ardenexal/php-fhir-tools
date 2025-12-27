<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Exception thrown when evaluating FHIRPath expressions fails.
 *
 * This exception is thrown when:
 * - Type mismatches occur during evaluation
 * - Invalid operations are attempted
 * - Resource navigation fails
 * - Function or operator execution fails
 *
 * @author FHIR Tools Contributors
 */
class EvaluationException extends FHIRPathException
{
    /**
     * Create an evaluation exception with detailed error information.
     *
     * @param string      $message           The error message
     * @param int         $line              The line number where evaluation failed
     * @param int         $column            The column number where evaluation failed
     * @param string      $expressionContext The expression context around the error
     * @param string|null $suggestion        Optional suggestion for fixing the error
     */
    public function __construct(
        string $message,
        int $line = 0,
        int $column = 0,
        string $expressionContext = '',
        ?string $suggestion = null
    ) {
        parent::__construct($message, $line, $column, $expressionContext, $suggestion);
    }

    /**
     * Create an evaluation exception for type mismatches.
     *
     * @param string $expectedType The expected type
     * @param string $actualType   The actual type found
     * @param string $context      The expression context
     */
    public static function typeMismatch(
        string $expectedType,
        string $actualType,
        string $context = ''
    ): self {
        $message = sprintf(
            'Type mismatch: expected %s but got %s',
            $expectedType,
            $actualType,
        );

        $suggestion = 'Ensure the expression operates on the correct types or use type conversion functions.';

        return new self($message, 0, 0, $context, $suggestion);
    }

    /**
     * Create an evaluation exception for invalid operations.
     *
     * @param string $operation The operation that failed
     * @param string $reason    The reason for failure
     * @param string $context   The expression context
     */
    public static function invalidOperation(
        string $operation,
        string $reason,
        string $context = ''
    ): self {
        $message = sprintf(
            'Invalid operation %s: %s',
            $operation,
            $reason,
        );

        $suggestion = 'Check that the operation is applicable to the current data.';

        return new self($message, 0, 0, $context, $suggestion);
    }

    /**
     * Create an evaluation exception for navigation failures.
     *
     * @param string $path    The path that failed
     * @param string $reason  The reason for failure
     * @param string $context The expression context
     */
    public static function navigationFailed(
        string $path,
        string $reason,
        string $context = ''
    ): self {
        $message = sprintf(
            'Navigation failed for path "%s": %s',
            $path,
            $reason,
        );

        $suggestion = 'Verify that the path exists in the FHIR resource structure.';

        return new self($message, 0, 0, $context, $suggestion);
    }

    /**
     * Create an evaluation exception for invalid function parameters.
     *
     * @param string $functionName  The function name
     * @param string $parameterName The parameter name
     * @param string $expectedType  The expected type/format
     */
    public static function invalidFunctionParameter(
        string $functionName,
        string $parameterName,
        string $expectedType
    ): self {
        $message = sprintf(
            "Function '%s' parameter '%s' must be %s",
            $functionName,
            $parameterName,
            $expectedType,
        );

        $suggestion = 'Check the function documentation for the correct parameter types.';

        return new self($message, 0, 0, '', $suggestion);
    }
}
