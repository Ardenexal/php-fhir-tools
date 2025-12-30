<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the TokenType enum.
 *
 * @author FHIR Tools Contributors
 */
class TokenTypeTest extends TestCase
{
    public function testIsKeyword(): void
    {
        self::assertTrue(TokenType::AND->isKeyword());
        self::assertTrue(TokenType::OR->isKeyword());
        self::assertTrue(TokenType::XOR->isKeyword());
        self::assertTrue(TokenType::IMPLIES->isKeyword());
        self::assertTrue(TokenType::AS->isKeyword());
        self::assertTrue(TokenType::IS->isKeyword());
        self::assertTrue(TokenType::IN->isKeyword());
        self::assertTrue(TokenType::CONTAINS->isKeyword());
        self::assertTrue(TokenType::DIV->isKeyword());
        self::assertTrue(TokenType::MOD->isKeyword());

        self::assertFalse(TokenType::IDENTIFIER->isKeyword());
        self::assertFalse(TokenType::STRING->isKeyword());
        self::assertFalse(TokenType::NUMBER->isKeyword());
    }

    public function testIsOperator(): void
    {
        self::assertTrue(TokenType::EQUALS->isOperator());
        self::assertTrue(TokenType::NOT_EQUALS->isOperator());
        self::assertTrue(TokenType::GREATER_THAN->isOperator());
        self::assertTrue(TokenType::PLUS->isOperator());
        self::assertTrue(TokenType::PIPE->isOperator());

        self::assertFalse(TokenType::IDENTIFIER->isOperator());
        self::assertFalse(TokenType::AND->isOperator()); // keyword, not operator
    }

    public function testIsLiteral(): void
    {
        self::assertTrue(TokenType::STRING->isLiteral());
        self::assertTrue(TokenType::NUMBER->isLiteral());
        self::assertTrue(TokenType::BOOLEAN->isLiteral());
        self::assertTrue(TokenType::NULL->isLiteral());
        self::assertTrue(TokenType::DATETIME->isLiteral());

        self::assertFalse(TokenType::IDENTIFIER->isLiteral());
        self::assertFalse(TokenType::EQUALS->isLiteral());
    }

    public function testIsDelimiter(): void
    {
        self::assertTrue(TokenType::LPAREN->isDelimiter());
        self::assertTrue(TokenType::RPAREN->isDelimiter());
        self::assertTrue(TokenType::DOT->isDelimiter());
        self::assertTrue(TokenType::COMMA->isDelimiter());

        self::assertFalse(TokenType::IDENTIFIER->isDelimiter());
        self::assertFalse(TokenType::EQUALS->isDelimiter());
    }
}
