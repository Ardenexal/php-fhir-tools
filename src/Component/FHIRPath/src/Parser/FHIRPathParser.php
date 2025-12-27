<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\SyntaxException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\BinaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\CollectionLiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExternalConstantNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\FunctionCallNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IdentifierNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IndexerNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\LiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\MemberAccessNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\TypeExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\UnaryOperatorNode;

/**
 * Recursive descent parser for FHIRPath expressions.
 *
 * Converts a sequence of tokens from the lexer into an Abstract Syntax Tree (AST)
 * following the FHIRPath 2.0 grammar. Handles operator precedence, function calls,
 * member access, and all FHIRPath constructs.
 *
 * Grammar (from FHIRPath spec):
 * - expression: term (('|') term)*
 * - term: factor (('and' | 'or' | 'xor' | 'implies') factor)*
 * - factor: invocation (('*' | '/' | 'div' | 'mod') invocation)*
 * - invocation: primary (typeExpression | invocationExpression)*
 * - primary: literal | externalConstant | '(' expression ')' | '{' '}' | function
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathParser
{
    /** @var array<Token> */
    private array $tokens = [];

    private int $current = 0;

    /**
     * Parse a FHIRPath expression from tokens.
     *
     * @param array<Token> $tokens The tokens to parse
     *
     * @return ExpressionNode The root node of the AST
     *
     * @throws SyntaxException If parsing fails
     */
    public function parse(array $tokens): ExpressionNode
    {
        $this->tokens  = $tokens;
        $this->current = 0;

        $expression = $this->parseExpression();

        // Ensure we've consumed all tokens except EOF
        if (!$this->isAtEnd()) {
            throw SyntaxException::unexpectedToken('end of expression', $this->peek()->toString(), $this->peek()->line, $this->peek()->column);
        }

        return $expression;
    }

    /**
     * Parse an expression (top-level rule).
     * expression: term (('|') term)*
     */
    private function parseExpression(): ExpressionNode
    {
        $left = $this->parseTerm();

        while ($this->match(TokenType::PIPE)) {
            $operator = $this->previous();
            $right    = $this->parseTerm();
            $left     = new BinaryOperatorNode(
                $left,
                $operator->type,
                $right,
                $operator->line,
                $operator->column,
            );
        }

        return $left;
    }

    /**
     * Parse a term (logical operators).
     * term: factor (('and' | 'or' | 'xor' | 'implies') factor)*
     */
    private function parseTerm(): ExpressionNode
    {
        $left = $this->parseFactor();

        while ($this->match(TokenType::AND, TokenType::OR, TokenType::XOR, TokenType::IMPLIES)) {
            $operator = $this->previous();
            $right    = $this->parseFactor();
            $left     = new BinaryOperatorNode(
                $left,
                $operator->type,
                $right,
                $operator->line,
                $operator->column,
            );
        }

        return $left;
    }

    /**
     * Parse a factor (multiplicative operators and comparisons).
     * This combines multiple precedence levels for simplicity.
     */
    private function parseFactor(): ExpressionNode
    {
        $left = $this->parseUnary();

        // Handle comparison operators
        while ($this->match(
            TokenType::EQUALS,
            TokenType::NOT_EQUALS,
            TokenType::EQUIVALENT,
            TokenType::NOT_EQUIVALENT,
            TokenType::GREATER_THAN,
            TokenType::LESS_THAN,
            TokenType::GREATER_EQUAL,
            TokenType::LESS_EQUAL,
            TokenType::IN,
            TokenType::CONTAINS,
        )) {
            $operator = $this->previous();
            $right    = $this->parseUnary();
            $left     = new BinaryOperatorNode(
                $left,
                $operator->type,
                $right,
                $operator->line,
                $operator->column,
            );
        }

        // Handle multiplicative operators
        while ($this->match(TokenType::MULTIPLY, TokenType::DIVIDE, TokenType::DIV, TokenType::MOD)) {
            $operator = $this->previous();
            $right    = $this->parseUnary();
            $left     = new BinaryOperatorNode(
                $left,
                $operator->type,
                $right,
                $operator->line,
                $operator->column,
            );
        }

        // Handle additive operators
        while ($this->match(TokenType::PLUS, TokenType::MINUS, TokenType::AMPERSAND)) {
            $operator = $this->previous();
            $right    = $this->parseUnary();
            $left     = new BinaryOperatorNode(
                $left,
                $operator->type,
                $right,
                $operator->line,
                $operator->column,
            );
        }

        return $left;
    }

    /**
     * Parse unary operators.
     */
    private function parseUnary(): ExpressionNode
    {
        if ($this->match(TokenType::MINUS, TokenType::PLUS)) {
            $operator = $this->previous();
            $operand  = $this->parseUnary();

            return new UnaryOperatorNode(
                $operator->type,
                $operand,
                $operator->line,
                $operator->column,
            );
        }

        return $this->parseInvocation();
    }

    /**
     * Parse an invocation (member access, indexer, type expressions).
     * invocation: primary (typeExpression | invocationExpression)*
     */
    private function parseInvocation(): ExpressionNode
    {
        $expression = $this->parsePrimary();

        while (true) {
            // Handle type expressions (is, as)
            if ($this->match(TokenType::IS, TokenType::AS)) {
                $operator   = $this->previous();
                $typeName   = $this->consume(TokenType::IDENTIFIER, 'type name');
                $expression = new TypeExpressionNode(
                    $expression,
                    $operator->type,
                    $typeName->value,
                    $operator->line,
                    $operator->column,
                );
                continue;
            }

            // Handle member access
            if ($this->match(TokenType::DOT)) {
                $dot        = $this->previous();
                $member     = $this->parsePrimary();
                $expression = new MemberAccessNode(
                    $expression,
                    $member,
                    $dot->line,
                    $dot->column,
                );
                continue;
            }

            // Handle indexer
            if ($this->match(TokenType::LBRACKET)) {
                $bracket = $this->previous();
                $index   = $this->parseExpression();
                $this->consume(TokenType::RBRACKET, ']');
                $expression = new IndexerNode(
                    $expression,
                    $index,
                    $bracket->line,
                    $bracket->column,
                );
                continue;
            }

            break;
        }

        return $expression;
    }

    /**
     * Parse a primary expression.
     * primary: literal | externalConstant | '(' expression ')' | '{' '}' | function | identifier
     */
    private function parsePrimary(): ExpressionNode
    {
        // Literals
        if ($this->peek()->type->isLiteral()) {
            $token = $this->advance();
            $value = match ($token->type) {
                TokenType::BOOLEAN => $token->value === 'true',
                TokenType::NULL    => null,
                TokenType::NUMBER  => $this->parseNumber($token->value),
                default            => $token->value,
            };

            return new LiteralNode($value, $token->type, $token->line, $token->column);
        }

        // External constants
        if ($this->match(TokenType::PERCENT)) {
            $percent = $this->previous();
            $name    = $this->consume(TokenType::IDENTIFIER, 'constant name');

            return new ExternalConstantNode($name->value, $percent->line, $percent->column);
        }

        // Parenthesized expression
        if ($this->match(TokenType::LPAREN)) {
            $paren      = $this->previous();
            $expression = $this->parseExpression();
            $this->consume(TokenType::RPAREN, ')');

            return $expression;
        }

        // Empty collection
        if ($this->match(TokenType::LBRACE)) {
            $brace    = $this->previous();
            $elements = [];

            if (!$this->check(TokenType::RBRACE)) {
                do {
                    $elements[] = $this->parseExpression();
                } while ($this->match(TokenType::COMMA));
            }

            $this->consume(TokenType::RBRACE, '}');

            return new CollectionLiteralNode($elements, $brace->line, $brace->column);
        }

        // Reserved identifiers
        if ($this->match(TokenType::THIS, TokenType::INDEX, TokenType::TOTAL)) {
            $token = $this->previous();

            return new IdentifierNode($token->value, $token->line, $token->column);
        }

        // Function call or identifier
        if ($this->match(TokenType::IDENTIFIER)) {
            $identifier = $this->previous();

            // Check if it's a function call
            if ($this->match(TokenType::LPAREN)) {
                $parameters = [];

                if (!$this->check(TokenType::RPAREN)) {
                    do {
                        $parameters[] = $this->parseExpression();
                    } while ($this->match(TokenType::COMMA));
                }

                $this->consume(TokenType::RPAREN, ')');

                return new FunctionCallNode(
                    $identifier->value,
                    $parameters,
                    $identifier->line,
                    $identifier->column,
                );
            }

            // Just an identifier
            return new IdentifierNode($identifier->value, $identifier->line, $identifier->column);
        }

        throw SyntaxException::unexpectedToken('expression', $this->peek()->toString(), $this->peek()->line, $this->peek()->column);
    }

    /**
     * Parse a number value.
     */
    private function parseNumber(string $value): int|float
    {
        if (str_contains($value, '.') || str_contains($value, 'e') || str_contains($value, 'E')) {
            return (float) $value;
        }

        return (int) $value;
    }

    /**
     * Check if current token matches any of the given types and advance if so.
     */
    private function match(TokenType ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->check($type)) {
                $this->advance();

                return true;
            }
        }

        return false;
    }

    /**
     * Check if current token is of the given type.
     */
    private function check(TokenType $type): bool
    {
        if ($this->isAtEnd()) {
            return false;
        }

        return $this->peek()->type === $type;
    }

    /**
     * Advance to the next token and return the previous one.
     */
    private function advance(): Token
    {
        if (!$this->isAtEnd()) {
            ++$this->current;
        }

        return $this->previous();
    }

    /**
     * Check if we're at the end of tokens.
     */
    private function isAtEnd(): bool
    {
        return $this->peek()->type === TokenType::EOF;
    }

    /**
     * Get the current token without advancing.
     */
    private function peek(): Token
    {
        return $this->tokens[$this->current];
    }

    /**
     * Get the previous token.
     */
    private function previous(): Token
    {
        return $this->tokens[$this->current - 1];
    }

    /**
     * Consume a token of the expected type or throw an error.
     */
    private function consume(TokenType $type, string $description): Token
    {
        if ($this->check($type)) {
            return $this->advance();
        }

        throw SyntaxException::unexpectedToken($description, $this->peek()->toString(), $this->peek()->line, $this->peek()->column);
    }
}
