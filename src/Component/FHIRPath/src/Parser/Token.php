<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

/**
 * Represents a single token in a FHIRPath expression.
 *
 * Tokens are immutable and contain information about the token type,
 * its value, and its position in the source expression for error reporting.
 *
 * @author FHIR Tools Contributors
 */
readonly class Token
{
    /**
     * Create a new token.
     *
     * @param TokenType $type     The type of this token
     * @param string    $value    The literal value of this token from the source
     * @param int       $line     The line number where this token appears (1-indexed)
     * @param int       $column   The column number where this token starts (1-indexed)
     * @param int       $position The absolute character position in the source (0-indexed)
     */
    public function __construct(
        public TokenType $type,
        public string $value,
        public int $line,
        public int $column,
        public int $position
    ) {
    }

    /**
     * Check if this token is of a specific type.
     *
     * @param TokenType $type The type to check against
     *
     * @return bool True if this token matches the specified type
     */
    public function is(TokenType $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Check if this token is one of several types.
     *
     * @param TokenType ...$types One or more types to check against
     *
     * @return bool True if this token matches any of the specified types
     */
    public function isOneOf(TokenType ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->type === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a string representation of this token for debugging.
     *
     * @return string A formatted string showing the token's type, value, and position
     */
    public function toString(): string
    {
        return sprintf(
            '%s(%s) at %d:%d',
            $this->type->value,
            $this->value !== '' ? $this->value : '<empty>',
            $this->line,
            $this->column,
        );
    }

    /**
     * Magic method to convert token to string.
     *
     * @return string The token's string representation
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
