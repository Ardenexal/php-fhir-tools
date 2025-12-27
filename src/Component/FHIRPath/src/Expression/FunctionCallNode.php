<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing a function call.
 *
 * Represents function invocations in FHIRPath expressions with their parameters.
 *
 * @author FHIR Tools Contributors
 */
class FunctionCallNode extends ExpressionNode
{
    /**
     * Create a new function call node.
     *
     * @param string                $name       The function name
     * @param array<ExpressionNode> $parameters The function parameters
     * @param int                   $line       The line number
     * @param int                   $column     The column number
     */
    public function __construct(
        private readonly string $name,
        private readonly array $parameters,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the function name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the function parameters.
     *
     * @return array<ExpressionNode>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitFunctionCall($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $params = array_map(fn (ExpressionNode $p) => $p->toString(), $this->parameters);

        return $this->name . '(' . implode(', ', $params) . ')';
    }
}
