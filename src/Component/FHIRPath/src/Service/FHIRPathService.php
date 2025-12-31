<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Service;

use Ardenexal\FHIRTools\Component\FHIRPath\Cache\ExpressionCacheInterface;
use Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache;
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
 * validation, compilation, and caching for improved performance.
 *
 * @author Ardenexal
 */
class FHIRPathService
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    private ExpressionCacheInterface $cache;

    /**
     * Create a new FHIRPath service.
     *
     * @param ExpressionCacheInterface|null $cache Optional cache implementation (defaults to in-memory cache)
     */
    public function __construct(?ExpressionCacheInterface $cache = null)
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
        $this->cache     = $cache ?? new InMemoryExpressionCache();
    }

    /**
     * Evaluate a FHIRPath expression against a resource.
     *
     * Uses caching to avoid re-parsing frequently used expressions.
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
        $compiled = $this->getOrCompile($expression);

        return $compiled->evaluate($resource, $context);
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
     * The compiled expression is also cached for future use.
     *
     * @param string $expression The FHIRPath expression to compile
     *
     * @return CompiledExpression The compiled expression
     *
     * @throws FHIRPathException If the expression is invalid
     */
    public function compile(string $expression): CompiledExpression
    {
        return $this->getOrCompile($expression);
    }

    /**
     * Get the expression cache.
     *
     * @return ExpressionCacheInterface The cache instance
     */
    public function getCache(): ExpressionCacheInterface
    {
        return $this->cache;
    }

    /**
     * Clear the expression cache.
     */
    public function clearCache(): void
    {
        $this->cache->clear();
    }

    /**
     * Get cache statistics.
     *
     * @return array{hits: int, misses: int, size: int} Cache statistics
     */
    public function getCacheStats(): array
    {
        return $this->cache->getStats();
    }

    /**
     * Get or compile an expression, using cache when available.
     *
     * @param string $expression The FHIRPath expression
     *
     * @return CompiledExpression The compiled expression
     *
     * @throws FHIRPathException If the expression is invalid
     */
    private function getOrCompile(string $expression): CompiledExpression
    {
        // Check cache first
        $cached = $this->cache->get($expression);
        if ($cached !== null) {
            return $cached;
        }

        // Parse and compile
        $ast      = $this->parse($expression);
        $compiled = new CompiledExpression($ast, $this->evaluator, $expression);

        // Store in cache
        $this->cache->set($expression, $compiled);

        return $compiled;
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
