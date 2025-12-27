<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;

/**
 * AST node representing a type expression (is/as).
 *
 * Represents type checking and type casting operations in FHIRPath.
 * For example, `value is Integer` or `value as Patient`.
 *
 * @author FHIR Tools Contributors
 */
class TypeExpressionNode extends ExpressionNode
{
    /**
     * Create a new type expression node.
     *
     * @param ExpressionNode $expression The expression to check/cast
     * @param TokenType      $operator   The operator (IS or AS)
     * @param string         $typeName   The type name to check/cast to
     * @param int            $line       The line number
     * @param int            $column     The column number
     */
    public function __construct(
        private readonly ExpressionNode $expression,
        private readonly TokenType $operator,
        private readonly string $typeName,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the expression being checked/cast.
     */
    public function getExpression(): ExpressionNode
    {
        return $this->expression;
    }

    /**
     * Get the operator (IS or AS).
     */
    public function getOperator(): TokenType
    {
        return $this->operator;
    }

    /**
     * Get the type name.
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitTypeExpression($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $operator = $this->operator === TokenType::IS ? ' is ' : ' as ';

        return '(' . $this->expression->toString() . $operator . $this->typeName . ')';
    }
}
