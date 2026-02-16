<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\TokenException;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use PHPUnit\Framework\TestCase;

/**
 * Comprehensive tests for the FHIRPathLexer class.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathLexerTest extends TestCase
{
    private FHIRPathLexer $lexer;

    protected function setUp(): void
    {
        $this->lexer = new FHIRPathLexer();
    }

    // Identifier and Keyword Tests

    public function testTokenizeSimpleIdentifier(): void
    {
        $tokens = $this->lexer->tokenize('name');

        self::assertCount(2, $tokens); // identifier + EOF
        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('name', $tokens[0]->value);
        self::assertEquals(TokenType::EOF, $tokens[1]->type);
    }

    public function testTokenizeMultipleIdentifiers(): void
    {
        $tokens = $this->lexer->tokenize('Patient name given');

        self::assertCount(4, $tokens); // 3 identifiers + EOF
        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('Patient', $tokens[0]->value);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[1]->type);
        self::assertEquals('name', $tokens[1]->value);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[2]->type);
        self::assertEquals('given', $tokens[2]->value);
    }

    public function testTokenizeKeywords(): void
    {
        $tokens = $this->lexer->tokenize('and or xor implies as is in contains div mod');

        self::assertEquals(TokenType::AND, $tokens[0]->type);
        self::assertEquals(TokenType::OR, $tokens[1]->type);
        self::assertEquals(TokenType::XOR, $tokens[2]->type);
        self::assertEquals(TokenType::IMPLIES, $tokens[3]->type);
        self::assertEquals(TokenType::AS, $tokens[4]->type);
        self::assertEquals(TokenType::IS, $tokens[5]->type);
        self::assertEquals(TokenType::IN, $tokens[6]->type);
        self::assertEquals(TokenType::CONTAINS, $tokens[7]->type);
        self::assertEquals(TokenType::DIV, $tokens[8]->type);
        self::assertEquals(TokenType::MOD, $tokens[9]->type);
    }

    public function testTokenizeBooleanLiterals(): void
    {
        $tokens = $this->lexer->tokenize('true false');

        self::assertEquals(TokenType::BOOLEAN, $tokens[0]->type);
        self::assertEquals('true', $tokens[0]->value);
        self::assertEquals(TokenType::BOOLEAN, $tokens[1]->type);
        self::assertEquals('false', $tokens[1]->value);
    }

    // Operator Tests

    public function testTokenizeComparisonOperators(): void
    {
        $tokens = $this->lexer->tokenize('= != ~ !~ > < >= <=');

        self::assertEquals(TokenType::EQUALS, $tokens[0]->type);
        self::assertEquals(TokenType::NOT_EQUALS, $tokens[1]->type);
        self::assertEquals(TokenType::EQUIVALENT, $tokens[2]->type);
        self::assertEquals(TokenType::NOT_EQUIVALENT, $tokens[3]->type);
        self::assertEquals(TokenType::GREATER_THAN, $tokens[4]->type);
        self::assertEquals(TokenType::LESS_THAN, $tokens[5]->type);
        self::assertEquals(TokenType::GREATER_EQUAL, $tokens[6]->type);
        self::assertEquals(TokenType::LESS_EQUAL, $tokens[7]->type);
    }

    public function testTokenizeArithmeticOperators(): void
    {
        $tokens = $this->lexer->tokenize('+ - * /');

        self::assertEquals(TokenType::PLUS, $tokens[0]->type);
        self::assertEquals(TokenType::MINUS, $tokens[1]->type);
        self::assertEquals(TokenType::MULTIPLY, $tokens[2]->type);
        self::assertEquals(TokenType::DIVIDE, $tokens[3]->type);
    }

    public function testTokenizeCollectionOperators(): void
    {
        $tokens = $this->lexer->tokenize('| &');

        self::assertEquals(TokenType::PIPE, $tokens[0]->type);
        self::assertEquals(TokenType::AMPERSAND, $tokens[1]->type);
    }

    // Delimiter Tests

    public function testTokenizeDelimiters(): void
    {
        $tokens = $this->lexer->tokenize('()[]{},.');

        self::assertEquals(TokenType::LPAREN, $tokens[0]->type);
        self::assertEquals(TokenType::RPAREN, $tokens[1]->type);
        self::assertEquals(TokenType::LBRACKET, $tokens[2]->type);
        self::assertEquals(TokenType::RBRACKET, $tokens[3]->type);
        self::assertEquals(TokenType::LBRACE, $tokens[4]->type);
        self::assertEquals(TokenType::RBRACE, $tokens[5]->type);
        self::assertEquals(TokenType::COMMA, $tokens[6]->type);
        self::assertEquals(TokenType::DOT, $tokens[7]->type);
    }

    // String Literal Tests

    public function testTokenizeSimpleString(): void
    {
        $tokens = $this->lexer->tokenize("'hello'");

        self::assertCount(2, $tokens);
        self::assertEquals(TokenType::STRING, $tokens[0]->type);
        self::assertEquals('hello', $tokens[0]->value);
    }

    public function testTokenizeStringWithEscapes(): void
    {
        $tokens = $this->lexer->tokenize("'it\\'s'");

        self::assertEquals(TokenType::STRING, $tokens[0]->type);
        self::assertEquals("it's", $tokens[0]->value);
    }

    public function testTokenizeStringWithVariousEscapes(): void
    {
        $tokens = $this->lexer->tokenize("'line\\nbreak\\ttab'");

        self::assertEquals(TokenType::STRING, $tokens[0]->type);
        self::assertEquals("line\nbreak\ttab", $tokens[0]->value);
    }

    public function testTokenizeStringWithBackslash(): void
    {
        $tokens = $this->lexer->tokenize("'back\\\\slash'");

        self::assertEquals(TokenType::STRING, $tokens[0]->type);
        self::assertEquals('back\\slash', $tokens[0]->value);
    }

    public function testTokenizeUnterminatedStringThrowsException(): void
    {
        $this->expectException(TokenException::class);
        $this->expectExceptionMessage('Unterminated string literal');

        $this->lexer->tokenize("'unterminated");
    }

    public function testTokenizeInvalidEscapeSequenceThrowsException(): void
    {
        $this->expectException(TokenException::class);
        $this->expectExceptionMessage('Invalid escape sequence');

        $this->lexer->tokenize("'invalid\\x'");
    }

    // Number Literal Tests

    public function testTokenizeInteger(): void
    {
        $tokens = $this->lexer->tokenize('42');

        self::assertEquals(TokenType::NUMBER, $tokens[0]->type);
        self::assertEquals('42', $tokens[0]->value);
    }

    public function testTokenizeDecimal(): void
    {
        $tokens = $this->lexer->tokenize('3.14');

        self::assertEquals(TokenType::NUMBER, $tokens[0]->type);
        self::assertEquals('3.14', $tokens[0]->value);
    }

    public function testTokenizeScientificNotation(): void
    {
        $tokens = $this->lexer->tokenize('2.5e10');

        self::assertEquals(TokenType::NUMBER, $tokens[0]->type);
        self::assertEquals('2.5e10', $tokens[0]->value);
    }

    public function testTokenizeScientificNotationWithNegativeExponent(): void
    {
        $tokens = $this->lexer->tokenize('1.5e-3');

        self::assertEquals(TokenType::NUMBER, $tokens[0]->type);
        self::assertEquals('1.5e-3', $tokens[0]->value);
    }

    // Quantity Literal Tests

    public function testTokenizeQuantity(): void
    {
        $tokens = $this->lexer->tokenize("5 'mg'");

        self::assertEquals(TokenType::QUANTITY, $tokens[0]->type);
        self::assertEquals("5 'mg'", $tokens[0]->value);
    }

    public function testTokenizeDecimalQuantity(): void
    {
        $tokens = $this->lexer->tokenize("10.5 'cm'");

        self::assertEquals(TokenType::QUANTITY, $tokens[0]->type);
        self::assertEquals("10.5 'cm'", $tokens[0]->value);
    }

    // DateTime and Time Literal Tests

    public function testTokenizeDate(): void
    {
        $tokens = $this->lexer->tokenize('@2023-12-25');

        self::assertEquals(TokenType::DATETIME, $tokens[0]->type);
        self::assertEquals('@2023-12-25', $tokens[0]->value);
    }

    public function testTokenizeDateTime(): void
    {
        $tokens = $this->lexer->tokenize('@2023-12-25T12:30:00');

        self::assertEquals(TokenType::DATETIME, $tokens[0]->type);
        self::assertEquals('@2023-12-25T12:30:00', $tokens[0]->value);
    }

    public function testTokenizeDateTimeWithTimezone(): void
    {
        $tokens = $this->lexer->tokenize('@2023-12-25T12:30:00+01:00');

        self::assertEquals(TokenType::DATETIME, $tokens[0]->type);
        self::assertEquals('@2023-12-25T12:30:00+01:00', $tokens[0]->value);
    }

    public function testTokenizeTime(): void
    {
        $tokens = $this->lexer->tokenize('@T12:30:00');

        self::assertEquals(TokenType::TIME, $tokens[0]->type);
        self::assertEquals('@T12:30:00', $tokens[0]->value);
    }

    // Reserved Identifier Tests

    public function testTokenizeReservedIdentifiers(): void
    {
        $tokens = $this->lexer->tokenize('$this $index $total');

        self::assertEquals(TokenType::THIS, $tokens[0]->type);
        self::assertEquals('$this', $tokens[0]->value);
        self::assertEquals(TokenType::INDEX, $tokens[1]->type);
        self::assertEquals('$index', $tokens[1]->value);
        self::assertEquals(TokenType::TOTAL, $tokens[2]->type);
        self::assertEquals('$total', $tokens[2]->value);
    }

    public function testTokenizeExternalConstant(): void
    {
        $tokens = $this->lexer->tokenize('%ucum');

        self::assertEquals(TokenType::PERCENT, $tokens[0]->type);
        self::assertEquals('%', $tokens[0]->value);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[1]->type);
        self::assertEquals('ucum', $tokens[1]->value);
    }

    // Complex Expression Tests

    public function testTokenizeSimplePathExpression(): void
    {
        $tokens = $this->lexer->tokenize('Patient.name.given');

        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('Patient', $tokens[0]->value);
        self::assertEquals(TokenType::DOT, $tokens[1]->type);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[2]->type);
        self::assertEquals('name', $tokens[2]->value);
        self::assertEquals(TokenType::DOT, $tokens[3]->type);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[4]->type);
        self::assertEquals('given', $tokens[4]->value);
    }

    public function testTokenizeFunctionCall(): void
    {
        $tokens = $this->lexer->tokenize('where(active = true)');

        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('where', $tokens[0]->value);
        self::assertEquals(TokenType::LPAREN, $tokens[1]->type);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[2]->type);
        self::assertEquals('active', $tokens[2]->value);
        self::assertEquals(TokenType::EQUALS, $tokens[3]->type);
        self::assertEquals(TokenType::BOOLEAN, $tokens[4]->type);
        self::assertEquals('true', $tokens[4]->value);
        self::assertEquals(TokenType::RPAREN, $tokens[5]->type);
    }

    public function testTokenizeComparisonExpression(): void
    {
        $tokens = $this->lexer->tokenize('age > 18');

        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('age', $tokens[0]->value);
        self::assertEquals(TokenType::GREATER_THAN, $tokens[1]->type);
        self::assertEquals(TokenType::NUMBER, $tokens[2]->type);
        self::assertEquals('18', $tokens[2]->value);
    }

    public function testTokenizeLogicalExpression(): void
    {
        $tokens = $this->lexer->tokenize("age > 18 and status = 'active'");

        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals(TokenType::GREATER_THAN, $tokens[1]->type);
        self::assertEquals(TokenType::NUMBER, $tokens[2]->type);
        self::assertEquals(TokenType::AND, $tokens[3]->type);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[4]->type);
        self::assertEquals(TokenType::EQUALS, $tokens[5]->type);
        self::assertEquals(TokenType::STRING, $tokens[6]->type);
    }

    // Position Tracking Tests

    public function testPositionTrackingForSingleLine(): void
    {
        $tokens = $this->lexer->tokenize('name age');

        self::assertEquals(1, $tokens[0]->line);
        self::assertEquals(1, $tokens[0]->column);
        self::assertEquals(0, $tokens[0]->position);

        self::assertEquals(1, $tokens[1]->line);
        self::assertEquals(6, $tokens[1]->column); // After "name "
        self::assertEquals(5, $tokens[1]->position);
    }

    public function testPositionTrackingForMultipleLines(): void
    {
        $tokens = $this->lexer->tokenize("name\nage");

        self::assertEquals(1, $tokens[0]->line);
        self::assertEquals(1, $tokens[0]->column);

        self::assertEquals(2, $tokens[1]->line);
        self::assertEquals(1, $tokens[1]->column);
    }

    // Whitespace Handling Tests

    public function testSkipWhitespace(): void
    {
        $tokens = $this->lexer->tokenize('  name   age  ');

        self::assertCount(3, $tokens); // 2 identifiers + EOF
        self::assertEquals('name', $tokens[0]->value);
        self::assertEquals('age', $tokens[1]->value);
    }

    public function testSkipNewlines(): void
    {
        $tokens = $this->lexer->tokenize("name\n\nage");

        self::assertCount(3, $tokens);
        self::assertEquals('name', $tokens[0]->value);
        self::assertEquals('age', $tokens[1]->value);
    }

    public function testSkipTabs(): void
    {
        $tokens = $this->lexer->tokenize("name\t\tage");

        self::assertCount(3, $tokens);
        self::assertEquals('name', $tokens[0]->value);
        self::assertEquals('age', $tokens[1]->value);
    }

    // Edge Cases

    public function testEmptyExpression(): void
    {
        $tokens = $this->lexer->tokenize('');

        self::assertCount(1, $tokens); // Just EOF
        self::assertEquals(TokenType::EOF, $tokens[0]->type);
    }

    public function testWhitespaceOnlyExpression(): void
    {
        $tokens = $this->lexer->tokenize("   \n\t  ");

        self::assertCount(1, $tokens); // Just EOF
        self::assertEquals(TokenType::EOF, $tokens[0]->type);
    }

    public function testUnexpectedCharacterThrowsException(): void
    {
        $this->expectException(TokenException::class);
        $this->expectExceptionMessage('Unexpected character');

        $this->lexer->tokenize('name # age');
    }

    public function testSingleExclamationThrowsException(): void
    {
        $this->expectException(TokenException::class);

        $this->lexer->tokenize('name ! age');
    }

    // Real-world FHIRPath Expression Tests

    public function testComplexFHIRPathExpression(): void
    {
        $expression = "Patient.name.where(use = 'official').given.first()";
        $tokens     = $this->lexer->tokenize($expression);

        self::assertGreaterThan(10, count($tokens));
        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals('Patient', $tokens[0]->value);
    }

    public function testFHIRPathWithQuantity(): void
    {
        $expression = "Observation.value > 5 'mg'";
        $tokens     = $this->lexer->tokenize($expression);

        self::assertEquals(TokenType::IDENTIFIER, $tokens[0]->type);
        self::assertEquals(TokenType::DOT, $tokens[1]->type);
        self::assertEquals(TokenType::IDENTIFIER, $tokens[2]->type);
        self::assertEquals(TokenType::GREATER_THAN, $tokens[3]->type);
        self::assertEquals(TokenType::QUANTITY, $tokens[4]->type);
    }
}
