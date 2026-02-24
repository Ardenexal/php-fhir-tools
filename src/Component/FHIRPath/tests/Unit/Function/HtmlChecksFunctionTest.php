<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FHIR htmlChecks() function.
 *
 * htmlChecks(): Boolean
 *  - Returns [true]  when the string is conformant FHIR Narrative xhtml.
 *  - Returns [false] when any FHIR Narrative constraint is violated.
 *  - Returns []      when input is not exactly one item or not a string.
 *
 * @author FHIR Tools Contributors
 */
final class HtmlChecksFunctionTest extends TestCase
{
    /** Minimal valid FHIR Narrative div. */
    private const VALID_DIV = '<div xmlns="http://www.w3.org/1999/xhtml"><p>Hello</p></div>';

    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // Valid input → true
    // -------------------------------------------------------------------------

    public function testValidXhtmlDivReturnsTrue(): void
    {
        $result = $this->evaluate('htmlChecks()', self::VALID_DIV);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testValidDivWithNestedElementsReturnsTrue(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<p>Some <b>bold</b> and <i>italic</i> text.</p>'
            . '<ul><li>Item 1</li><li>Item 2</li></ul>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertTrue($result->first());
    }

    public function testValidDivWithImgAndAltReturnsTrue(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<img src="photo.png" alt="A photo"/>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertTrue($result->first());
    }

    /** Chained access: property navigation then htmlChecks(). */
    public function testChainedNavigationFromNarrativeDiv(): void
    {
        $resource = [
            'text' => [
                'status'     => 'generated',
                'divContent' => self::VALID_DIV,  // Renamed to avoid 'div' keyword conflict
            ],
        ];

        // Navigate to divContent property and call htmlChecks
        $result = $this->evaluate('text.divContent.htmlChecks()', $resource);

        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // Malformed XML → false
    // -------------------------------------------------------------------------

    public function testMalformedXmlReturnsFalse(): void
    {
        $result = $this->evaluate('htmlChecks()', '<div xmlns="http://www.w3.org/1999/xhtml"><p>unclosed');

        self::assertCount(1, $result->toArray());
        self::assertFalse($result->first());
    }

    public function testEmptyStringReturnsFalse(): void
    {
        $result = $this->evaluate('htmlChecks()', '');

        self::assertFalse($result->first());
    }

    public function testPlainTextReturnsFalse(): void
    {
        $result = $this->evaluate('htmlChecks()', 'just plain text');

        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // Root element constraints → false
    // -------------------------------------------------------------------------

    public function testRootElementNotDivReturnsFalse(): void
    {
        $xhtml = '<p xmlns="http://www.w3.org/1999/xhtml">text</p>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    public function testMissingXhtmlNamespaceReturnsFalse(): void
    {
        $result = $this->evaluate('htmlChecks()', '<div><p>Hello</p></div>');

        self::assertFalse($result->first());
    }

    public function testWrongNamespaceReturnsFalse(): void
    {
        $xhtml = '<div xmlns="http://example.com/notxhtml"><p>text</p></div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // Forbidden elements → false
    // -------------------------------------------------------------------------

    #[DataProvider('forbiddenElementProvider')]
    public function testForbiddenElementReturnsFalse(string $tag, string $content): void
    {
        $xhtml = sprintf(
            '<div xmlns="http://www.w3.org/1999/xhtml"><p>text</p>%s</div>',
            $content,
        );

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first(), "Expected false for forbidden element <{$tag}>");
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function forbiddenElementProvider(): array
    {
        return [
            'script'  => ['script', '<script>alert(1)</script>'],
            'form'    => ['form', '<form action="/post"><input type="text"/></form>'],
            'head'    => ['head', '<head><title>Title</title></head>'],
            'html'    => ['html', '<html></html>'],
            'base'    => ['base', '<base href="http://example.com"/>'],
            'link'    => ['link', '<link rel="stylesheet" href="style.css"/>'],
            'meta'    => ['meta', '<meta charset="utf-8"/>'],
            'frame'   => ['frame', '<frame src="page.html"/>'],
            'iframe'  => ['iframe', '<iframe src="page.html"></iframe>'],
            'object'  => ['object', '<object data="file.swf"></object>'],
        ];
    }

    // -------------------------------------------------------------------------
    // Event handler attributes → false
    // -------------------------------------------------------------------------

    public function testOnclickAttributeReturnsFalse(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<p onclick="alert(1)">click me</p>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    public function testOnloadAttributeReturnsFalse(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml" onload="init()"><p>text</p></div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    public function testOnmouseoverAttributeReturnsFalse(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<a href="#" onmouseover="highlight()">link</a>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // img without alt → false
    // -------------------------------------------------------------------------

    public function testImgWithoutAltReturnsFalse(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<img src="photo.png"/>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertFalse($result->first());
    }

    public function testImgWithAltReturnsTrue(): void
    {
        $xhtml = '<div xmlns="http://www.w3.org/1999/xhtml">'
            . '<img src="photo.png" alt=""/>'
            . '</div>';

        $result = $this->evaluate('htmlChecks()', $xhtml);

        self::assertTrue($result->first());
    }

    // -------------------------------------------------------------------------
    // Invalid input collection → empty
    // -------------------------------------------------------------------------

    public function testEmptyCollectionInputReturnsEmpty(): void
    {
        $result = $this->evaluate('{}.htmlChecks()', null);

        self::assertTrue($result->isEmpty());
    }

    public function testMultipleItemsInInputReturnsEmpty(): void
    {
        // (a | b) produces a two-item collection when values are different
        $VALID_DIV_2 = '<div xmlns="http://www.w3.org/1999/xhtml"><p>World</p></div>';
        $resource    = ['a' => self::VALID_DIV, 'b' => $VALID_DIV_2];
        $result      = $this->evaluate('(a | b).htmlChecks()', $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testNonStringInputReturnsEmpty(): void
    {
        // Pass an integer literal as the root — htmlChecks() receives [42]
        $result = $this->evaluate('htmlChecks()', 42);

        self::assertTrue($result->isEmpty());
    }
}
