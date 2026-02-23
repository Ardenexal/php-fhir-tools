<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\SyntaxException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\BinaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\CollectionLiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExternalConstantNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\FunctionCallNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IdentifierNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IndexerNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\LiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\MemberAccessNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\TypeExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\UnaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

/**
 * Comprehensive tests for the FHIRPathParser class.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathParserTest extends TestCase
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    protected function setUp(): void
    {
        $this->lexer  = new FHIRPathLexer();
        $this->parser = new FHIRPathParser();
    }

    // Literal Tests

    public function testParseStringLiteral(): void
    {
        $tokens = $this->lexer->tokenize("'hello'");
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(LiteralNode::class, $ast);
        self::assertEquals('hello', $ast->getValue());
        self::assertEquals(TokenType::STRING, $ast->getType());
    }

    public function testParseNumberLiteral(): void
    {
        $tokens = $this->lexer->tokenize('42');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(LiteralNode::class, $ast);
        self::assertEquals(42, $ast->getValue());
    }

    public function testParseDecimalLiteral(): void
    {
        $tokens = $this->lexer->tokenize('3.14');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(LiteralNode::class, $ast);
        self::assertEquals(3.14, $ast->getValue());
    }

    public function testParseBooleanLiteral(): void
    {
        $tokens = $this->lexer->tokenize('true');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(LiteralNode::class, $ast);
        self::assertTrue($ast->getValue());
    }

    // Identifier Tests

    public function testParseSimpleIdentifier(): void
    {
        $tokens = $this->lexer->tokenize('name');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(IdentifierNode::class, $ast);
        self::assertEquals('name', $ast->getName());
    }

    // Member Access Tests

    public function testParseMemberAccess(): void
    {
        $tokens = $this->lexer->tokenize('Patient.name');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(MemberAccessNode::class, $ast);
        self::assertInstanceOf(IdentifierNode::class, $ast->getObject());
        self::assertInstanceOf(IdentifierNode::class, $ast->getMember());
        self::assertEquals('Patient', $ast->getObject()->getName());
        self::assertEquals('name', $ast->getMember()->getName());
    }

    public function testParseChainedMemberAccess(): void
    {
        $tokens = $this->lexer->tokenize('Patient.name.given');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(MemberAccessNode::class, $ast);
        // The structure should be: (Patient.name).given
        self::assertInstanceOf(MemberAccessNode::class, $ast->getObject());
        self::assertInstanceOf(IdentifierNode::class, $ast->getMember());
    }

    // Function Call Tests

    public function testParseFunctionCallNoParams(): void
    {
        $tokens = $this->lexer->tokenize('first()');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(FunctionCallNode::class, $ast);
        self::assertEquals('first', $ast->getName());
        self::assertEmpty($ast->getParameters());
    }

    public function testParseFunctionCallWithOneParam(): void
    {
        $tokens = $this->lexer->tokenize('where(active)');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(FunctionCallNode::class, $ast);
        self::assertEquals('where', $ast->getName());
        self::assertCount(1, $ast->getParameters());
        self::assertInstanceOf(IdentifierNode::class, $ast->getParameters()[0]);
    }

    public function testParseFunctionCallWithMultipleParams(): void
    {
        $tokens = $this->lexer->tokenize('substring(1, 5)');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(FunctionCallNode::class, $ast);
        self::assertEquals('substring', $ast->getName());
        self::assertCount(2, $ast->getParameters());
    }

    // Binary Operator Tests

    public function testParseAddition(): void
    {
        $tokens = $this->lexer->tokenize('1 + 2');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::PLUS, $ast->getOperator());
    }

    public function testParseComparison(): void
    {
        $tokens = $this->lexer->tokenize('age > 18');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::GREATER_THAN, $ast->getOperator());
    }

    public function testParseLogicalAnd(): void
    {
        $tokens = $this->lexer->tokenize('active and verified');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::AND, $ast->getOperator());
    }

    public function testParseEquality(): void
    {
        $tokens = $this->lexer->tokenize("status = 'active'");
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::EQUALS, $ast->getOperator());
    }

    // Unary Operator Tests

    public function testParseUnaryMinus(): void
    {
        $tokens = $this->lexer->tokenize('-5');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(UnaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::MINUS, $ast->getOperator());
        self::assertInstanceOf(LiteralNode::class, $ast->getOperand());
    }

    // Indexer Tests

    public function testParseIndexer(): void
    {
        $tokens = $this->lexer->tokenize('name[0]');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(IndexerNode::class, $ast);
        self::assertInstanceOf(IdentifierNode::class, $ast->getCollection());
        self::assertInstanceOf(LiteralNode::class, $ast->getIndex());
    }

    // Type Expression Tests

    public function testParseTypeIs(): void
    {
        $tokens = $this->lexer->tokenize('value is Integer');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(TypeExpressionNode::class, $ast);
        self::assertEquals(TokenType::IS, $ast->getOperator());
        self::assertEquals('Integer', $ast->getTypeName());
    }

    public function testParseTypeAs(): void
    {
        $tokens = $this->lexer->tokenize('value as Patient');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(TypeExpressionNode::class, $ast);
        self::assertEquals(TokenType::AS, $ast->getOperator());
        self::assertEquals('Patient', $ast->getTypeName());
    }

    public function testParseTypeIsFunctionCall(): void
    {
        // resource.is(Patient) is the function-call form — must produce
        // the same TypeExpressionNode AST as the infix form (resource is Patient)
        $tokens = $this->lexer->tokenize('resource.is(Patient)');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(TypeExpressionNode::class, $ast);
        self::assertEquals(TokenType::IS, $ast->getOperator());
        self::assertEquals('Patient', $ast->getTypeName());
    }

    public function testParseTypeAsFunctionCall(): void
    {
        // resource.as(Patient) is the function-call form — must produce
        // the same TypeExpressionNode AST as the infix form (resource as Patient)
        $tokens = $this->lexer->tokenize('resource.as(Patient)');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(TypeExpressionNode::class, $ast);
        self::assertEquals(TokenType::AS, $ast->getOperator());
        self::assertEquals('Patient', $ast->getTypeName());
    }

    // External Constant Tests

    public function testParseExternalConstant(): void
    {
        $tokens = $this->lexer->tokenize('%ucum');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(ExternalConstantNode::class, $ast);
        self::assertEquals('ucum', $ast->getName());
    }

    // Collection Literal Tests

    public function testParseEmptyCollection(): void
    {
        $tokens = $this->lexer->tokenize('{}');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(CollectionLiteralNode::class, $ast);
        self::assertEmpty($ast->getElements());
    }

    public function testParseCollectionLiteral(): void
    {
        $tokens = $this->lexer->tokenize('{1, 2, 3}');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(CollectionLiteralNode::class, $ast);
        self::assertCount(3, $ast->getElements());
    }

    // Parentheses Tests

    public function testParseParenthesizedExpression(): void
    {
        $tokens = $this->lexer->tokenize('(1 + 2)');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::PLUS, $ast->getOperator());
    }

    // Complex Expression Tests

    public function testParseComplexPathExpression(): void
    {
        $tokens = $this->lexer->tokenize("Patient.name.where(use = 'official').given.first()");
        $ast    = $this->parser->parse($tokens);

        // Should parse without throwing exceptions
        self::assertNotNull($ast);
    }

    public function testParseMemberAccessWithFunctionCall(): void
    {
        $tokens = $this->lexer->tokenize('name.first()');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(MemberAccessNode::class, $ast);
        self::assertInstanceOf(FunctionCallNode::class, $ast->getMember());
    }

    public function testParseLogicalExpression(): void
    {
        $tokens = $this->lexer->tokenize("age > 18 and status = 'active'");
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::AND, $ast->getOperator());
    }

    public function testParseUnionOperator(): void
    {
        $tokens = $this->lexer->tokenize('name | telecom');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::PIPE, $ast->getOperator());
    }

    // Reserved Identifier Tests

    public function testParseReservedIdentifiers(): void
    {
        $tokens = $this->lexer->tokenize('$this');
        $ast    = $this->parser->parse($tokens);

        self::assertInstanceOf(IdentifierNode::class, $ast);
        self::assertEquals('$this', $ast->getName());
    }

    // Error Cases

    public function testParseUnexpectedTokenThrowsException(): void
    {
        $this->expectException(SyntaxException::class);

        $tokens = $this->lexer->tokenize('name ]');
        $this->parser->parse($tokens);
    }

    public function testParseUnterminatedParenthesesThrowsException(): void
    {
        $this->expectException(SyntaxException::class);

        $tokens = $this->lexer->tokenize('(name');
        $this->parser->parse($tokens);
    }

    public function testParseUnterminatedBracketThrowsException(): void
    {
        $this->expectException(SyntaxException::class);

        $tokens = $this->lexer->tokenize('name[0');
        $this->parser->parse($tokens);
    }

    public function testParseUnterminatedBraceThrowsException(): void
    {
        $this->expectException(SyntaxException::class);

        $tokens = $this->lexer->tokenize('{1, 2');
        $this->parser->parse($tokens);
    }

    // Precedence Tests

    public function testMultiplicativeBindsTighterThanAdditive(): void
    {
        // 1+2*3+4 must parse as (1+(2*3))+4, not ((1+2)*3)+4
        // Root must be additive (+), right child is literal 4,
        // left child is another additive (+) whose right is multiplicative (*)
        $tokens = $this->lexer->tokenize('1+2*3+4');
        $ast    = $this->parser->parse($tokens);

        // Root: +
        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::PLUS, $ast->getOperator());

        // Right of root: literal 4
        self::assertInstanceOf(LiteralNode::class, $ast->getRight());
        self::assertEquals(4, $ast->getRight()->getValue());

        // Left of root: another +
        $leftPlus = $ast->getLeft();
        self::assertInstanceOf(BinaryOperatorNode::class, $leftPlus);
        self::assertEquals(TokenType::PLUS, $leftPlus->getOperator());

        // Right of inner +: the multiply (2*3)
        $multiply = $leftPlus->getRight();
        self::assertInstanceOf(BinaryOperatorNode::class, $multiply);
        self::assertEquals(TokenType::MULTIPLY, $multiply->getOperator());
        self::assertEquals(2, $multiply->getLeft()->getValue());
        self::assertEquals(3, $multiply->getRight()->getValue());
    }

    public function testTwoMultiplicativeGroupsAddedTogether(): void
    {
        // 2*3+4*5 must parse as (2*3)+(4*5)
        $tokens = $this->lexer->tokenize('2*3+4*5');
        $ast    = $this->parser->parse($tokens);

        // Root: +
        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::PLUS, $ast->getOperator());

        // Left: (2*3)
        $leftMul = $ast->getLeft();
        self::assertInstanceOf(BinaryOperatorNode::class, $leftMul);
        self::assertEquals(TokenType::MULTIPLY, $leftMul->getOperator());
        self::assertEquals(2, $leftMul->getLeft()->getValue());
        self::assertEquals(3, $leftMul->getRight()->getValue());

        // Right: (4*5)
        $rightMul = $ast->getRight();
        self::assertInstanceOf(BinaryOperatorNode::class, $rightMul);
        self::assertEquals(TokenType::MULTIPLY, $rightMul->getOperator());
        self::assertEquals(4, $rightMul->getLeft()->getValue());
        self::assertEquals(5, $rightMul->getRight()->getValue());
    }

    public function testComparisonBindsLooserThanAdditive(): void
    {
        // 1+2 > 0 must parse as (1+2) > 0, not 1+(2>0)
        $tokens = $this->lexer->tokenize('1+2 > 0');
        $ast    = $this->parser->parse($tokens);

        // Root: >
        self::assertInstanceOf(BinaryOperatorNode::class, $ast);
        self::assertEquals(TokenType::GREATER_THAN, $ast->getOperator());

        // Left of >: (1+2)
        $add = $ast->getLeft();
        self::assertInstanceOf(BinaryOperatorNode::class, $add);
        self::assertEquals(TokenType::PLUS, $add->getOperator());

        // Right of >: 0
        self::assertInstanceOf(LiteralNode::class, $ast->getRight());
        self::assertEquals(0, $ast->getRight()->getValue());
    }

    // ToString Tests

    public function testToStringForSimpleExpression(): void
    {
        $tokens = $this->lexer->tokenize('name');
        $ast    = $this->parser->parse($tokens);

        self::assertEquals('name', $ast->toString());
    }

    public function testToStringForBinaryOperator(): void
    {
        $tokens = $this->lexer->tokenize('a + b');
        $ast    = $this->parser->parse($tokens);

        self::assertStringContainsString('+', $ast->toString());
    }
}
