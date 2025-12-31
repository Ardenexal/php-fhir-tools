<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Service;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;

/**
 * High-level service for FHIRPath expression evaluation.
 *
 * Provides a convenient API for evaluating FHIRPath expressions against FHIR resources.
 * Handles lexing, parsing, and evaluation in a single call, with support for expression
 * validation and compilation for improved performance.
 *
 * @author Ardenexal
 */
class FHIRPathService
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    public function __construct()
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    /**
     * Evaluate a FHIRPath expression against a resource.
     *
     * @param string                 $expression The FHIRPath expression to evaluate
     * @param mixed                  $resource   The FHIR resource or data to evaluate against
     * @param EvaluationContext|null $context    Optional evaluation context
     *
     * @return Collection The result collection
     *
     * @throws FHIRPathException If the expression is invalid or evaluation fails
     */
    public function evaluate(string $expression, mixed $resource, ?EvaluationContext $context = null): Collection
    {
        $ast = $this->parse($expression);

        return $this->evaluator->evaluate($ast, $resource, $context);
    }

    /**
     * Validate a FHIRPath expression without evaluating it.
     *
     * Checks if the expression is syntactically valid by attempting to parse it.
     *
     * @param string $expression The FHIRPath expression to validate
     *
     * @return bool True if the expression is valid, false otherwise
     */
    public function validate(string $expression): bool
    {
        try {
            $this->parse($expression);

            return true;
        } catch (FHIRPathException) {
            return false;
        }
    }

    /**
     * Compile a FHIRPath expression for repeated evaluation.
     *
     * Parses the expression once and returns a compiled expression that can be
     * evaluated multiple times against different resources for improved performance.
     *
     * @param string $expression The FHIRPath expression to compile
     *
     * @return CompiledExpression The compiled expression
     *
     * @throws FHIRPathException If the expression is invalid
     */
    public function compile(string $expression): CompiledExpression
    {
        $ast = $this->parse($expression);

        return new CompiledExpression($ast, $this->evaluator, $expression);
    }

    /**
     * Parse a FHIRPath expression into an AST.
     *
     * @param string $expression The FHIRPath expression to parse
     *
     * @return ExpressionNode The root node of the AST
     *
     * @throws FHIRPathException If parsing fails
     */
    private function parse(string $expression): ExpressionNode
    {
        $tokens = $this->lexer->tokenize($expression);

        return $this->parser->parse($tokens);
    }
}
