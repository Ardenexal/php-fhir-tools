<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Expression;

use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IdentifierNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\LiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

/**
 * Tests for expression nodes.
 *
 * @author FHIR Tools Contributors
 */
class ExpressionNodeTest extends TestCase
{
    public function testLiteralNodeString(): void
    {
        $node = new LiteralNode('hello', TokenType::STRING, 1, 1);

        self::assertEquals('hello', $node->getValue());
        self::assertEquals(TokenType::STRING, $node->getType());
        self::assertEquals(1, $node->getLine());
        self::assertEquals(1, $node->getColumn());
        self::assertStringContainsString('hello', $node->toString());
    }

    public function testLiteralNodeNumber(): void
    {
        $node = new LiteralNode(42, TokenType::NUMBER, 1, 1);

        self::assertEquals(42, $node->getValue());
        self::assertEquals('42', $node->toString());
    }

    public function testLiteralNodeBoolean(): void
    {
        $node = new LiteralNode(true, TokenType::BOOLEAN, 1, 1);

        self::assertTrue($node->getValue());
        self::assertEquals('true', $node->toString());
    }

    public function testIdentifierNode(): void
    {
        $node = new IdentifierNode('name', 1, 1);

        self::assertEquals('name', $node->getName());
        self::assertEquals('name', $node->toString());
    }
}
