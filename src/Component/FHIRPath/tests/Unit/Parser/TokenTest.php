<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\Token;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Token class.
 *
 * @author FHIR Tools Contributors
 */
class TokenTest extends TestCase
{
    public function testConstructorSetsProperties(): void
    {
        $token = new Token(
            TokenType::IDENTIFIER,
            'name',
            10,
            5,
            42,
        );

        self::assertEquals(TokenType::IDENTIFIER, $token->type);
        self::assertEquals('name', $token->value);
        self::assertEquals(10, $token->line);
        self::assertEquals(5, $token->column);
        self::assertEquals(42, $token->position);
    }

    public function testIsMethod(): void
    {
        $token = new Token(TokenType::IDENTIFIER, 'test', 1, 1, 0);

        self::assertTrue($token->is(TokenType::IDENTIFIER));
        self::assertFalse($token->is(TokenType::STRING));
    }

    public function testIsOneOfMethod(): void
    {
        $token = new Token(TokenType::PLUS, '+', 1, 1, 0);

        self::assertTrue($token->isOneOf(TokenType::PLUS, TokenType::MINUS));
        self::assertTrue($token->isOneOf(TokenType::MULTIPLY, TokenType::PLUS));
        self::assertFalse($token->isOneOf(TokenType::MULTIPLY, TokenType::DIVIDE));
    }

    public function testToString(): void
    {
        $token = new Token(TokenType::STRING, 'hello', 2, 10, 50);

        $str = $token->toString();

        self::assertStringContainsString('STRING', $str);
        self::assertStringContainsString('hello', $str);
        self::assertStringContainsString('2:10', $str);
    }

    public function testToStringWithEmptyValue(): void
    {
        $token = new Token(TokenType::EOF, '', 1, 1, 100);

        $str = $token->toString();

        self::assertStringContainsString('EOF', $str);
        self::assertStringContainsString('<empty>', $str);
    }

    public function testMagicToString(): void
    {
        $token = new Token(TokenType::NUMBER, '42', 1, 1, 0);

        $str = (string) $token;

        self::assertStringContainsString('NUMBER', $str);
        self::assertStringContainsString('42', $str);
    }
}
