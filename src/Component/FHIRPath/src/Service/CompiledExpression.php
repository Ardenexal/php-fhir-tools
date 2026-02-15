<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Service;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * Represents a compiled FHIRPath expression that can be evaluated multiple times.
 *
 * Pre-parses and caches the expression AST for improved performance when evaluating
 * the same expression against multiple resources. This avoids re-parsing the expression
 * on each evaluation.
 *
 * @author Ardenexal
 */
class CompiledExpression
{
    /**
     * Create a new compiled expression.
     *
     * @param ExpressionNode    $ast        The pre-parsed expression AST
     * @param FHIRPathEvaluator $evaluator  The evaluator to use
     * @param string            $expression The original expression string
     */
    public function __construct(
        private readonly ExpressionNode $ast,
        private readonly FHIRPathEvaluator $evaluator,
        private readonly string $expression
    ) {
    }

    /**
     * Evaluate the compiled expression against a resource.
     *
     * @param mixed                  $resource The FHIR resource or data to evaluate against
     * @param EvaluationContext|null $context  Optional evaluation context
     *
     * @return Collection The result collection
     *
     * @throws EvaluationException If evaluation fails
     */
    public function evaluate(mixed $resource, ?EvaluationContext $context = null): Collection
    {
        return $this->evaluator->evaluate($this->ast, $resource, $context);
    }

    /**
     * Get the original expression string.
     *
     * @return string The expression string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * Get the parsed AST.
     *
     * @return ExpressionNode The expression AST
     */
    public function getAst(): ExpressionNode
    {
        return $this->ast;
    }

    /**
     * Get the string representation of this compiled expression.
     *
     * @return string The expression string
     */
    public function __toString(): string
    {
        return $this->expression;
    }
}
