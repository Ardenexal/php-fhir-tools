<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\TokenException;

/**
 * Lexical analyzer for FHIRPath expressions.
 *
 * The lexer tokenizes a FHIRPath expression string into a sequence of tokens
 * that can be processed by the parser. It handles:
 * - Keywords and identifiers
 * - Operators (single and multi-character)
 * - Literals (strings, numbers, booleans, dates, quantities)
 * - Delimiters
 * - Position tracking for error reporting
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathLexer
{
    private string $input = '';

    private int $position = 0;

    private int $line = 1;

    private int $column = 1;

    /** @var array<Token> */
    private array $tokens = [];

    /** @var array<string, TokenType> Keyword to token type mapping */
    private const KEYWORDS = [
        'and'      => TokenType::AND,
        'or'       => TokenType::OR,
        'xor'      => TokenType::XOR,
        'implies'  => TokenType::IMPLIES,
        'as'       => TokenType::AS,
        'is'       => TokenType::IS,
        'in'       => TokenType::IN,
        'contains' => TokenType::CONTAINS,
        'div'      => TokenType::DIV,
        'mod'      => TokenType::MOD,
        'true'     => TokenType::BOOLEAN,
        'false'    => TokenType::BOOLEAN,
    ];

    /**
     * Tokenize a FHIRPath expression into a list of tokens.
     *
     * @param string $expression The FHIRPath expression to tokenize
     *
     * @return array<Token> Array of tokens including EOF token at the end
     *
     * @throws TokenException If lexical analysis fails
     */
    public function tokenize(string $expression): array
    {
        $this->input    = $expression;
        $this->position = 0;
        $this->line     = 1;
        $this->column   = 1;
        $this->tokens   = [];

        while (!$this->isAtEnd()) {
            $this->scanToken();
        }

        // Add EOF token
        $this->tokens[] = new Token(
            TokenType::EOF,
            '',
            $this->line,
            $this->column,
            $this->position,
        );

        return $this->tokens;
    }

    /**
     * Scan and add the next token from the input.
     *
     * @throws TokenException If an invalid token is encountered
     */
    private function scanToken(): void
    {
        $this->skipWhitespace();

        if ($this->isAtEnd()) {
            return;
        }

        $start       = $this->position;
        $startLine   = $this->line;
        $startColumn = $this->column;

        $char = $this->advance();

        // Single-character tokens
        switch ($char) {
            case '(':
                $this->addToken(TokenType::LPAREN, '(', $startLine, $startColumn, $start);

                return;
            case ')':
                $this->addToken(TokenType::RPAREN, ')', $startLine, $startColumn, $start);

                return;
            case '[':
                $this->addToken(TokenType::LBRACKET, '[', $startLine, $startColumn, $start);

                return;
            case ']':
                $this->addToken(TokenType::RBRACKET, ']', $startLine, $startColumn, $start);

                return;
            case '{':
                $this->addToken(TokenType::LBRACE, '{', $startLine, $startColumn, $start);

                return;
            case '}':
                $this->addToken(TokenType::RBRACE, '}', $startLine, $startColumn, $start);

                return;
            case ',':
                $this->addToken(TokenType::COMMA, ',', $startLine, $startColumn, $start);

                return;
            case '.':
                $this->addToken(TokenType::DOT, '.', $startLine, $startColumn, $start);

                return;
            case '+':
                $this->addToken(TokenType::PLUS, '+', $startLine, $startColumn, $start);

                return;
            case '-':
                $this->addToken(TokenType::MINUS, '-', $startLine, $startColumn, $start);

                return;
            case '*':
                $this->addToken(TokenType::MULTIPLY, '*', $startLine, $startColumn, $start);

                return;
            case '|':
                $this->addToken(TokenType::PIPE, '|', $startLine, $startColumn, $start);

                return;
            case '&':
                $this->addToken(TokenType::AMPERSAND, '&', $startLine, $startColumn, $start);

                return;
        }

        // Multi-character operators
        if ($char === '=') {
            $this->addToken(TokenType::EQUALS, '=', $startLine, $startColumn, $start);

            return;
        }

        if ($char === '!') {
            if ($this->match('=')) {
                $this->addToken(TokenType::NOT_EQUALS, '!=', $startLine, $startColumn, $start);

                return;
            }
            if ($this->match('~')) {
                $this->addToken(TokenType::NOT_EQUIVALENT, '!~', $startLine, $startColumn, $start);

                return;
            }
            throw TokenException::unexpectedCharacter('!', $startLine, $startColumn, $this->getContext($start));
        }

        if ($char === '~') {
            $this->addToken(TokenType::EQUIVALENT, '~', $startLine, $startColumn, $start);

            return;
        }

        if ($char === '>') {
            if ($this->match('=')) {
                $this->addToken(TokenType::GREATER_EQUAL, '>=', $startLine, $startColumn, $start);
            } else {
                $this->addToken(TokenType::GREATER_THAN, '>', $startLine, $startColumn, $start);
            }

            return;
        }

        if ($char === '<') {
            if ($this->match('=')) {
                $this->addToken(TokenType::LESS_EQUAL, '<=', $startLine, $startColumn, $start);
            } else {
                $this->addToken(TokenType::LESS_THAN, '<', $startLine, $startColumn, $start);
            }

            return;
        }

        if ($char === '/') {
            $this->addToken(TokenType::DIVIDE, '/', $startLine, $startColumn, $start);

            return;
        }

        // String literals
        if ($char === "'") {
            $this->scanString($startLine, $startColumn, $start);

            return;
        }

        // DateTime and Time literals
        if ($char === '@') {
            $this->scanDateTime($startLine, $startColumn, $start);

            return;
        }

        // Reserved identifiers starting with $
        if ($char === '$') {
            $this->scanReservedIdentifier($startLine, $startColumn, $start);

            return;
        }

        // External constants starting with %
        if ($char === '%') {
            $this->scanExternalConstant($startLine, $startColumn, $start);

            return;
        }

        // Numbers
        if ($this->isDigit($char)) {
            $this->scanNumber($char, $startLine, $startColumn, $start);

            return;
        }

        // Identifiers and keywords
        if ($this->isAlpha($char) || $char === '_') {
            $this->scanIdentifier($char, $startLine, $startColumn, $start);

            return;
        }

        // If we get here, we have an unexpected character
        throw TokenException::unexpectedCharacter($char, $startLine, $startColumn, $this->getContext($start));
    }

    /**
     * Scan a string literal.
     *
     * @throws TokenException If the string is unterminated or contains invalid escapes
     */
    private function scanString(int $startLine, int $startColumn, int $start): void
    {
        $value = '';

        while (!$this->isAtEnd() && $this->peek() !== "'") {
            if ($this->peek() === '\\') {
                $this->advance(); // consume backslash
                $escaped = $this->advance();

                $value .= match ($escaped) {
                    "'"     => "'",
                    '"'     => '"',
                    '\\'    => '\\',
                    't'     => "\t",
                    'n'     => "\n",
                    'r'     => "\r",
                    'f'     => "\f",
                    'u'     => $this->scanUnicodeEscape($startLine, $startColumn),
                    default => throw TokenException::invalidEscapeSequence('\\' . $escaped, $this->line, $this->column - 1, $this->getContext($start))
                };
            } else {
                $value .= $this->advance();
            }
        }

        if ($this->isAtEnd()) {
            throw TokenException::unterminatedString($startLine, $startColumn, $this->getContext($start));
        }

        // Consume closing quote
        $this->advance();

        $this->addToken(TokenType::STRING, $value, $startLine, $startColumn, $start);
    }

    /**
     * Scan a Unicode escape sequence (\uXXXX).
     *
     * @throws TokenException If the Unicode sequence is invalid
     */
    private function scanUnicodeEscape(int $startLine, int $startColumn): string
    {
        $hex = '';
        for ($i = 0; $i < 4; ++$i) {
            if ($this->isAtEnd() || !$this->isHexDigit($this->peek())) {
                throw TokenException::invalidEscapeSequence('\\u' . $hex, $this->line, $this->column, '');
            }
            $hex .= $this->advance();
        }

        return mb_chr((int) hexdec($hex), 'UTF-8');
    }

    /**
     * Scan a number literal (integer, decimal, or scientific notation).
     */
    private function scanNumber(string $firstChar, int $startLine, int $startColumn, int $start): void
    {
        $value = $firstChar;

        // Consume integer part
        while ($this->isDigit($this->peek())) {
            $value .= $this->advance();
        }

        // Check for decimal part
        if ($this->peek() === '.' && $this->isDigit($this->peek(1))) {
            $value .= $this->advance(); // consume '.'
            while ($this->isDigit($this->peek())) {
                $value .= $this->advance();
            }
        }

        // Check for scientific notation
        if ($this->peek() === 'e' || $this->peek() === 'E') {
            $value .= $this->advance(); // consume 'e' or 'E'
            if ($this->peek() === '+' || $this->peek() === '-') {
                $value .= $this->advance();
            }
            if (!$this->isDigit($this->peek())) {
                throw TokenException::invalidNumber($value, $startLine, $startColumn, $this->getContext($start));
            }
            while ($this->isDigit($this->peek())) {
                $value .= $this->advance();
            }
        }

        // Check for quantity unit
        if ($this->peek() === ' ' && $this->peek(1) === "'") {
            $this->advance(); // consume space
            $this->advance(); // consume opening quote

            $unit = '';
            while (!$this->isAtEnd() && $this->peek() !== "'") {
                $unit .= $this->advance();
            }

            if ($this->isAtEnd()) {
                throw TokenException::unterminatedString($startLine, $startColumn, $this->getContext($start));
            }

            $this->advance(); // consume closing quote

            $this->addToken(TokenType::QUANTITY, $value . " '" . $unit . "'", $startLine, $startColumn, $start);

            return;
        }

        $this->addToken(TokenType::NUMBER, $value, $startLine, $startColumn, $start);
    }

    /**
     * Scan a DateTime or Time literal.
     */
    private function scanDateTime(int $startLine, int $startColumn, int $start): void
    {
        $value = '@';

        // Check for Time literal (@T...)
        if ($this->peek() === 'T') {
            $value .= $this->advance();
            // Scan time part
            while (!$this->isAtEnd() && ($this->isDigit($this->peek()) || $this->peek() === ':' || $this->peek() === '.')) {
                $value .= $this->advance();
            }
            $this->addToken(TokenType::TIME, $value, $startLine, $startColumn, $start);

            return;
        }

        // Scan date part (YYYY-MM-DD)
        while (!$this->isAtEnd() && ($this->isDigit($this->peek()) || $this->peek() === '-')) {
            $value .= $this->advance();
        }

        // Check for time part
        if ($this->peek() === 'T') {
            $value .= $this->advance();
            // Scan time and timezone
            while (!$this->isAtEnd() && ($this->isDigit($this->peek()) || $this->peek() === ':' || $this->peek() === '.' || $this->peek() === '+' || $this->peek() === '-' || $this->peek() === 'Z')) {
                $value .= $this->advance();
            }
        }

        $this->addToken(TokenType::DATETIME, $value, $startLine, $startColumn, $start);
    }

    /**
     * Scan a reserved identifier ($this, $index, $total).
     */
    private function scanReservedIdentifier(int $startLine, int $startColumn, int $start): void
    {
        $value = '$';

        while ($this->isAlphaNumeric($this->peek())) {
            $value .= $this->advance();
        }

        $type = match ($value) {
            '$this'  => TokenType::THIS,
            '$index' => TokenType::INDEX,
            '$total' => TokenType::TOTAL,
            default  => TokenType::IDENTIFIER, // Treat as regular identifier if not recognized
        };

        $this->addToken($type, $value, $startLine, $startColumn, $start);
    }

    /**
     * Scan an external constant (%identifier).
     */
    private function scanExternalConstant(int $startLine, int $startColumn, int $start): void
    {
        $this->addToken(TokenType::PERCENT, '%', $startLine, $startColumn, $start);
    }

    /**
     * Scan an identifier or keyword.
     */
    private function scanIdentifier(string $firstChar, int $startLine, int $startColumn, int $start): void
    {
        $value = $firstChar;

        while ($this->isAlphaNumeric($this->peek())) {
            $value .= $this->advance();
        }

        // Check if it's a keyword
        $type = self::KEYWORDS[strtolower($value)] ?? TokenType::IDENTIFIER;

        $this->addToken($type, $value, $startLine, $startColumn, $start);
    }

    /**
     * Add a token to the tokens list.
     */
    private function addToken(TokenType $type, string $value, int $line, int $column, int $position): void
    {
        $this->tokens[] = new Token($type, $value, $line, $column, $position);
    }

    /**
     * Skip whitespace characters.
     */
    private function skipWhitespace(): void
    {
        while (!$this->isAtEnd()) {
            $char = $this->peek();
            if ($char === ' ' || $char === "\t" || $char === "\r" || $char === "\n") {
                $this->advance();
            } else {
                break;
            }
        }
    }

    /**
     * Advance to the next character and return it.
     */
    private function advance(): string
    {
        if ($this->isAtEnd()) {
            return "\0";
        }

        $char = $this->input[$this->position++];

        if ($char === "\n") {
            ++$this->line;
            $this->column = 1;
        } else {
            ++$this->column;
        }

        return $char;
    }

    /**
     * Peek at the current character without advancing.
     */
    private function peek(int $offset = 0): string
    {
        $pos = $this->position + $offset;
        if ($pos >= strlen($this->input)) {
            return "\0";
        }

        return $this->input[$pos];
    }

    /**
     * Check if the current character matches the expected character and advance if it does.
     */
    private function match(string $expected): bool
    {
        if ($this->isAtEnd() || $this->peek() !== $expected) {
            return false;
        }
        $this->advance();

        return true;
    }

    /**
     * Check if we've reached the end of the input.
     */
    private function isAtEnd(): bool
    {
        return $this->position >= strlen($this->input);
    }

    /**
     * Check if a character is a digit.
     */
    private function isDigit(string $char): bool
    {
        return $char >= '0' && $char <= '9';
    }

    /**
     * Check if a character is a hexadecimal digit.
     */
    private function isHexDigit(string $char): bool
    {
        return ($char >= '0' && $char <= '9')
               || ($char >= 'a' && $char <= 'f')
               || ($char >= 'A' && $char <= 'F');
    }

    /**
     * Check if a character is alphabetic.
     */
    private function isAlpha(string $char): bool
    {
        return ($char >= 'a' && $char <= 'z')
               || ($char >= 'A' && $char <= 'Z')
               || $char === '_';
    }

    /**
     * Check if a character is alphanumeric.
     */
    private function isAlphaNumeric(string $char): bool
    {
        return $this->isAlpha($char) || $this->isDigit($char);
    }

    /**
     * Get context string around a position for error messages.
     */
    private function getContext(int $position, int $radius = 20): string
    {
        $start = max(0, $position - $radius);
        $end   = min(strlen($this->input), $position + $radius);

        $context = substr($this->input, $start, $end - $start);

        if ($start > 0) {
            $context = '...' . $context;
        }
        if ($end < strlen($this->input)) {
            $context .= '...';
        }

        return $context;
    }
}
