<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Exception thrown when tokenization (lexical analysis) fails.
 *
 * This exception is thrown when:
 * - Invalid characters are encountered
 * - Unterminated string literals are found
 * - Malformed number literals are encountered
 * - Invalid escape sequences are used
 *
 * @author FHIR Tools Contributors
 */
class TokenException extends ParseException
{
    /**
     * Create a token exception for unterminated string literals.
     *
     * @param int    $line    The line number where the string started
     * @param int    $column  The column number where the string started
     * @param string $context The expression context
     */
    public static function unterminatedString(
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = 'Unterminated string literal';
        $suggestion = "Add a closing quote ' to terminate the string.";

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a token exception for invalid escape sequences.
     *
     * @param string $sequence The invalid escape sequence
     * @param int    $line     The line number
     * @param int    $column   The column number
     * @param string $context  The expression context
     */
    public static function invalidEscapeSequence(
        string $sequence,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Invalid escape sequence: %s', $sequence);
        $suggestion = "Use valid escape sequences: \\', \\\", \\\\, \\t, \\n, \\r, \\f, or \\uXXXX";

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a token exception for invalid number formats.
     *
     * @param string $value   The invalid number value
     * @param int    $line    The line number
     * @param int    $column  The column number
     * @param string $context The expression context
     */
    public static function invalidNumber(
        string $value,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Invalid number format: %s', $value);
        $suggestion = 'Use valid number formats: 42, 3.14, or 2.5e10';

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a token exception for unexpected characters.
     *
     * @param string $char    The unexpected character
     * @param int    $line    The line number
     * @param int    $column  The column number
     * @param string $context The expression context
     */
    public static function unexpectedCharacter(
        string $char,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message = sprintf(
            'Unexpected character: %s',
            $char === "\0" ? '<EOF>' : "'{$char}'",
        );
        $suggestion = 'Check for invalid characters or missing quotes around strings.';

        return new self($message, $line, $column, $context, $suggestion);
    }

    /**
     * Create a token exception for invalid datetime literals.
     *
     * @param string $value   The invalid datetime value
     * @param int    $line    The line number
     * @param int    $column  The column number
     * @param string $context The expression context
     */
    public static function invalidDateTime(
        string $value,
        int $line,
        int $column,
        string $context = ''
    ): self {
        $message    = sprintf('Invalid date/time format: %s', $value);
        $suggestion = 'Use valid formats: @2023-12-25, @2023-12-25T12:30:00, or @T12:30:00';

        return new self($message, $line, $column, $context, $suggestion);
    }
}
