<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

/**
 * FHIRPath token types enumeration.
 *
 * Defines all possible token types that can appear in a FHIRPath expression,
 * including literals, keywords, operators, delimiters, and special tokens.
 *
 * @author FHIR Tools Contributors
 */
enum TokenType: string
{
    // Literals
    case STRING   = 'STRING';
    case NUMBER   = 'NUMBER';
    case BOOLEAN  = 'BOOLEAN';
    case NULL     = 'NULL';
    case DATETIME = 'DATETIME';
    case TIME     = 'TIME';
    case QUANTITY = 'QUANTITY';

    // Identifiers and Keywords
    case IDENTIFIER = 'IDENTIFIER';
    case AND        = 'AND';
    case OR         = 'OR';
    case XOR        = 'XOR';
    case IMPLIES    = 'IMPLIES';
    case AS         = 'AS';
    case IS         = 'IS';
    case IN         = 'IN';
    case CONTAINS   = 'CONTAINS';
    case DIV        = 'DIV';
    case MOD        = 'MOD';

    // Reserved Identifiers
    case THIS  = 'THIS';           // $this
    case INDEX = 'INDEX';         // $index
    case TOTAL = 'TOTAL';         // $total

    // Comparison Operators
    case EQUALS         = 'EQUALS';                  // =
    case NOT_EQUALS     = 'NOT_EQUALS';          // !=
    case EQUIVALENT     = 'EQUIVALENT';          // ~
    case NOT_EQUIVALENT = 'NOT_EQUIVALENT';  // !~
    case GREATER_THAN   = 'GREATER_THAN';      // >
    case LESS_THAN      = 'LESS_THAN';            // <
    case GREATER_EQUAL  = 'GREATER_EQUAL';    // >=
    case LESS_EQUAL     = 'LESS_EQUAL';          // <=

    // Arithmetic Operators
    case PLUS     = 'PLUS';                      // +
    case MINUS    = 'MINUS';                    // -
    case MULTIPLY = 'MULTIPLY';              // *
    case DIVIDE   = 'DIVIDE';                  // /

    // Delimiters
    case DOT      = 'DOT';                        // .
    case COMMA    = 'COMMA';                    // ,
    case LPAREN   = 'LPAREN';                  // (
    case RPAREN   = 'RPAREN';                  // )
    case LBRACKET = 'LBRACKET';              // [
    case RBRACKET = 'RBRACKET';              // ]
    case LBRACE   = 'LBRACE';                  // {
    case RBRACE   = 'RBRACE';                  // }

    // Special
    case PIPE      = 'PIPE';                      // |
    case AMPERSAND = 'AMPERSAND';            // &
    case PERCENT   = 'PERCENT';                // % (external constant prefix)
    case DOLLAR    = 'DOLLAR';                  // $ (reserved identifier prefix)

    // End of file
    case EOF = 'EOF';

    /**
     * Check if this token type is a keyword.
     */
    public function isKeyword(): bool
    {
        return match ($this) {
            self::AND, self::OR, self::XOR, self::IMPLIES,
            self::AS, self::IS, self::IN, self::CONTAINS,
            self::DIV, self::MOD => true,
            default => false,
        };
    }

    /**
     * Check if this token type is an operator.
     */
    public function isOperator(): bool
    {
        return match ($this) {
            self::EQUALS, self::NOT_EQUALS, self::EQUIVALENT, self::NOT_EQUIVALENT,
            self::GREATER_THAN, self::LESS_THAN, self::GREATER_EQUAL, self::LESS_EQUAL,
            self::PLUS, self::MINUS, self::MULTIPLY, self::DIVIDE,
            self::PIPE, self::AMPERSAND => true,
            default => false,
        };
    }

    /**
     * Check if this token type is a literal.
     */
    public function isLiteral(): bool
    {
        return match ($this) {
            self::STRING, self::NUMBER, self::BOOLEAN, self::NULL,
            self::DATETIME, self::TIME, self::QUANTITY => true,
            default => false,
        };
    }

    /**
     * Check if this token type is a delimiter.
     */
    public function isDelimiter(): bool
    {
        return match ($this) {
            self::DOT, self::COMMA, self::LPAREN, self::RPAREN,
            self::LBRACKET, self::RBRACKET, self::LBRACE, self::RBRACE => true,
            default => false,
        };
    }
}
