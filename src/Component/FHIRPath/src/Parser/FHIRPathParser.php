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
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;

/**
 * Recursive descent parser for FHIRPath expressions.
 *
 * Converts a sequence of tokens from the lexer into an Abstract Syntax Tree (AST)
 * following the FHIRPath 2.0 grammar. Handles operator precedence, function calls,
 * member access, and all FHIRPath constructs.
 *
 * Grammar (from FHIRPath spec, ordered highest to lowest precedence):
 * - expression:      term
 * - term:            comparison (('and' | 'or' | 'xor' | 'implies') comparison)*
 * - comparison:      union (('=' | '!=' | '~' | '!~' | '>' | '<' | '>=' | '<=' | 'in' | 'contains') union)*
 * - union:           additive (('|') additive)*
 * - additive:        multiplicative (('+' | '-' | '&') multiplicative)*
 * - multiplicative:  unary (('*' | '/' | 'div' | 'mod') unary)*
 * - unary:           invocation | ('-' | '+') unary
 * - invocation:      primary (typeExpression | '.' primary | '[' expression ']')*
 * - primary:         literal | externalConstant | '(' expression ')' | '{' '}' | function | identifier
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
     * expression: term
     */
    private function parseExpression(): ExpressionNode
    {
        return $this->parseTerm();
    }

    /**
     * Parse a term (logical operators).
     * term: comparison (('and' | 'or' | 'xor' | 'implies') comparison)*
     */
    private function parseTerm(): ExpressionNode
    {
        $left = $this->parseComparison();

        while ($this->match(TokenType::AND, TokenType::OR, TokenType::XOR, TokenType::IMPLIES)) {
            $operator = $this->previous();
            $right    = $this->parseComparison();
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
     * Parse a union expression.
     * union: additive (('|') additive)*
     *
     * Per the FHIRPath spec, the union operator has higher precedence than
     * comparison and equality operators, so `a = b | c` means `a = (b | c)`.
     */
    private function parseUnion(): ExpressionNode
    {
        $left = $this->parseAdditive();

        while ($this->match(TokenType::PIPE)) {
            $operator = $this->previous();
            $right    = $this->parseAdditive();
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
     * Parse a comparison expression.
     * comparison: union (('=' | '!=' | '~' | '!~' | '>' | '<' | '>=' | '<=' | 'in' | 'contains') union)*
     */
    private function parseComparison(): ExpressionNode
    {
        $left = $this->parseUnion();

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
            $right    = $this->parseUnion();
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
     * Parse an additive expression.
     * additive: multiplicative (('+' | '-' | '&') multiplicative)*
     */
    private function parseAdditive(): ExpressionNode
    {
        $left = $this->parseMultiplicative();

        while ($this->match(TokenType::PLUS, TokenType::MINUS, TokenType::AMPERSAND)) {
            $operator = $this->previous();
            $right    = $this->parseMultiplicative();
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
     * Parse a multiplicative expression.
     * multiplicative: unary (('*' | '/' | 'div' | 'mod') unary)*
     */
    private function parseMultiplicative(): ExpressionNode
    {
        $left = $this->parseUnary();

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
                $typeName   = $this->parseTypeName();
                $expression = new TypeExpressionNode(
                    $expression,
                    $operator->type,
                    $typeName,
                    $operator->line,
                    $operator->column,
                );
                continue;
            }

            // Handle member access
            if ($this->match(TokenType::DOT)) {
                $dot = $this->previous();

                // Handle .is(Type) and .as(Type) — function-call form of type expressions.
                // FHIRPath spec: resource.is(Patient) ≡ resource is Patient
                // Type specifier may be qualified: .is(System.Boolean), .as(FHIR.Patient)
                if (
                    ($this->check(TokenType::IS) || $this->check(TokenType::AS))
                    && $this->checkNext(TokenType::LPAREN)
                ) {
                    $keyword  = $this->advance(); // consume IS/AS keyword
                    $this->advance();             // consume LPAREN
                    $typeName = $this->parseTypeName();
                    $this->consume(TokenType::RPAREN, ')');
                    $expression = new TypeExpressionNode(
                        $expression,
                        $keyword->type,
                        $typeName,
                        $keyword->line,
                        $keyword->column,
                    );
                    continue;
                }

                // Handle .contains('x') — 'contains' is lexed as TokenType::CONTAINS (keyword),
                // not IDENTIFIER, so parsePrimary() won't treat it as a function call.
                // Parse it explicitly as a FunctionCallNode named 'contains'.
                if ($this->check(TokenType::CONTAINS) && $this->checkNext(TokenType::LPAREN)) {
                    $keyword = $this->advance(); // consume CONTAINS
                    $this->advance();            // consume LPAREN
                    $arguments = [];
                    if (!$this->check(TokenType::RPAREN)) {
                        $arguments[] = $this->parseExpression();
                        while ($this->match(TokenType::COMMA)) {
                            $arguments[] = $this->parseExpression();
                        }
                    }
                    $this->consume(TokenType::RPAREN, ')');
                    $funcNode   = new FunctionCallNode('contains', $arguments, $keyword->line, $keyword->column);
                    $expression = new MemberAccessNode($expression, $funcNode, $keyword->line, $keyword->column);
                    continue;
                }

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

        // Contextual keywords: 'contains' and 'in' can appear as property names in expression
        // position (e.g. repeat(contains), expansion.contains). When followed by '(' treat as
        // a function call; otherwise treat as an identifier (member/property name).
        if ($this->match(TokenType::CONTAINS, TokenType::IN)) {
            $token = $this->previous();

            if ($this->match(TokenType::LPAREN)) {
                $parameters = [];

                if (!$this->check(TokenType::RPAREN)) {
                    do {
                        $parameters[] = $this->parseExpression();
                    } while ($this->match(TokenType::COMMA));
                }

                $this->consume(TokenType::RPAREN, ')');

                return new FunctionCallNode($token->value, $parameters, $token->line, $token->column);
            }

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
     * Parse a type specifier, which may be a simple identifier or a qualified
     * name of the form Namespace.TypeName (e.g. System.Boolean, FHIR.Patient).
     *
     * Consumes one or two IDENTIFIER tokens (with an optional DOT in between)
     * and returns the combined name as a string.
     */
    private function parseTypeName(): string
    {
        $first  = $this->consume(TokenType::IDENTIFIER, 'type name');
        $result = $first->value;

        // Check for a qualified name: Identifier DOT Identifier
        // (e.g. System.Boolean, FHIR.Patient)
        if ($this->check(TokenType::DOT) && $this->checkNext(TokenType::IDENTIFIER)) {
            $this->advance(); // consume DOT
            $second = $this->consume(TokenType::IDENTIFIER, 'type name');
            $result .= '.' . $second->value;
        }

        return $result;
    }

    /**
     * Parse a number value.
     *
     * Integers are returned as PHP int. Decimal numbers are wrapped in FHIRPathDecimal
     * to preserve their exact string representation for bcmath arithmetic, avoiding
     * IEEE 754 floating-point rounding errors in FHIRPath expressions.
     */
    private function parseNumber(string $value): int|FHIRPathDecimal
    {
        if (str_contains($value, '.') || str_contains($value, 'e') || str_contains($value, 'E')) {
            return new FHIRPathDecimal($value);
        }

        return (int) $value;
    }

    /**
     * Check if current token matches any of the given types and advance if so.
     *
     * @phpstan-impure
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
     * Check if the next token (one ahead of current) is of the given type.
     */
    private function checkNext(TokenType $type): bool
    {
        $next = $this->current + 1;

        if ($next >= count($this->tokens)) {
            return false;
        }

        return $this->tokens[$next]->type === $type;
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
     *
     * @phpstan-impure
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
