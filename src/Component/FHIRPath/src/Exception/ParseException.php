<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Exception thrown when parsing FHIRPath expressions fails.
 *
 * This exception is thrown when:
 * - The expression has invalid syntax
 * - Unexpected tokens are encountered
 * - The expression structure is malformed
 *
 * @author FHIR Tools Contributors
 */
class ParseException extends FHIRPathException
{
    /**
     * Create a parse exception with detailed error information.
     *
     * @param string      $message           The error message
     * @param int         $line              The line number where parsing failed
     * @param int         $column            The column number where parsing failed
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
     * Create a parse exception for an unexpected token.
     *
     * @param string $expected The expected token type
     * @param string $actual   The actual token found
     * @param int    $line     The line number
     * @param int    $column   The column number
     * @param string $context  The expression context
     */
    public static function unexpectedToken(
        string $expected,
        string $actual,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message = sprintf(
            'Expected %s but found %s',
            $expected,
            $actual,
        );

        $suggestion = 'Check the expression syntax and ensure all operators and functions are used correctly.';

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a parse exception for invalid syntax.
     *
     * @param string $details Details about the syntax error
     * @param int    $line    The line number
     * @param int    $column  The column number
     * @param string $context The expression context
     */
    public static function invalidSyntax(
        string $details,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Invalid syntax: %s', $details);
        $suggestion = 'Review the FHIRPath specification for correct syntax.';

        return new self($message, $line, $column, $context, $suggestion);
    }
}
