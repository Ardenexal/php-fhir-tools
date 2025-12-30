<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;

/**
 * AST node representing a unary operator expression.
 *
 * Represents unary operations in FHIRPath such as negation (-) or positive (+).
 *
 * @author FHIR Tools Contributors
 */
class UnaryOperatorNode extends ExpressionNode
{
    /**
     * Create a new unary operator node.
     *
     * @param TokenType      $operator The operator (PLUS or MINUS)
     * @param ExpressionNode $operand  The operand
     * @param int            $line     The line number
     * @param int            $column   The column number
     */
    public function __construct(
        private readonly TokenType $operator,
        private readonly ExpressionNode $operand,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the operator.
     */
    public function getOperator(): TokenType
    {
        return $this->operator;
    }

    /**
     * Get the operand.
     */
    public function getOperand(): ExpressionNode
    {
        return $this->operand;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitUnaryOperator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $operatorStr = $this->operator === TokenType::MINUS ? '-' : '+';

        return '(' . $operatorStr . $this->operand->toString() . ')';
    }
}
