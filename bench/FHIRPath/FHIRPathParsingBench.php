<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\FHIRPath;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for FHIRPath lexing and parsing (no evaluation).
 *
 * Isolates the parse phase so regressions in the lexer or parser are visible
 * independently of evaluator performance.
 */
#[Bench\BeforeMethods(['setUp'])]
class FHIRPathParsingBench
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    public function setUp(): void
    {
        $this->lexer  = new FHIRPathLexer();
        $this->parser = new FHIRPathParser();
    }

    /**
     * Tokenize and parse a simple three-segment property-navigation path.
     */
    public function benchParseSimple(): void
    {
        $tokens = $this->lexer->tokenize('Patient.name.given');
        $this->parser->parse($tokens);
    }

    /**
     * Tokenize and parse a complex expression with function calls, boolean operators,
     * and chained member access.
     */
    public function benchParseComplex(): void
    {
        $tokens = $this->lexer->tokenize(
            "Patient.name.where(use = 'official' and family.exists()).given.first()",
        );
        $this->parser->parse($tokens);
    }

    /**
     * Tokenize and parse a deeply nested expression mixing arithmetic, comparisons,
     * and multiple function calls — representative of real-world CQL-style paths.
     */
    public function benchParseDeep(): void
    {
        $tokens = $this->lexer->tokenize(
            'Patient.name.where(use = \'official\').given.where($this.length() > 3 and $this.startsWith(\'Jo\')).first()',
        );
        $this->parser->parse($tokens);
    }
}
