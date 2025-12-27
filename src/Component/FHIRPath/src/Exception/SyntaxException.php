<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Exception thrown when parsing encounters a syntax error.
 *
 * This exception is thrown when:
 * - The parser encounters an unexpected token
 * - The expression structure doesn't match the grammar
 * - Required tokens are missing
 *
 * @author FHIR Tools Contributors
 */
class SyntaxException extends ParseException
{
    /**
     * Create a syntax exception for an unexpected token.
     *
     * @param string $expected Description of what was expected
     * @param string $found    Description of what was found
     * @param int    $line     The line number
     * @param int    $column   The column number
     * @param string $context  The expression context
     */
    public static function unexpectedToken(
        string $expected,
        string $found,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Expected %s but found %s', $expected, $found);
        $suggestion = 'Check the expression syntax and ensure all parts are correctly formed.';

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a syntax exception for unexpected end of expression.
     *
     * @param string $expected Description of what was expected
     * @param int    $line     The line number
     * @param int    $column   The column number
     * @param string $context  The expression context
     */
    public static function unexpectedEnd(
        string $expected,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Unexpected end of expression; expected %s', $expected);
        $suggestion = 'Complete the expression or remove incomplete parts.';

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a syntax exception for invalid expression structure.
     *
     * @param string $details Details about the structural error
     * @param int    $line    The line number
     * @param int    $column  The column number
     * @param string $context The expression context
     */
    public static function invalidStructure(
        string $details,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Invalid expression structure: %s', $details);
        $suggestion = 'Review the FHIRPath grammar and ensure the expression follows the correct structure.';

        return new self($message, $line, $column, $context, $suggestion);
    }
}
