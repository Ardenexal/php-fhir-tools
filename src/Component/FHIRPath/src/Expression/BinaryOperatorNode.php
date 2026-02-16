<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;

/**
 * AST node representing a binary operator expression.
 *
 * Represents binary operations in FHIRPath such as addition, comparison,
 * logical operations, etc.
 *
 * @author FHIR Tools Contributors
 */
class BinaryOperatorNode extends ExpressionNode
{
    /**
     * Create a new binary operator node.
     *
     * @param ExpressionNode $left     The left operand
     * @param TokenType      $operator The operator
     * @param ExpressionNode $right    The right operand
     * @param int            $line     The line number
     * @param int            $column   The column number
     */
    public function __construct(
        private readonly ExpressionNode $left,
        private readonly TokenType $operator,
        private readonly ExpressionNode $right,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the left operand.
     */
    public function getLeft(): ExpressionNode
    {
        return $this->left;
    }

    /**
     * Get the operator.
     */
    public function getOperator(): TokenType
    {
        return $this->operator;
    }

    /**
     * Get the right operand.
     */
    public function getRight(): ExpressionNode
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitBinaryOperator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        $operatorStr = match ($this->operator) {
            TokenType::AND            => ' and ',
            TokenType::OR             => ' or ',
            TokenType::XOR            => ' xor ',
            TokenType::IMPLIES        => ' implies ',
            TokenType::EQUALS         => ' = ',
            TokenType::NOT_EQUALS     => ' != ',
            TokenType::EQUIVALENT     => ' ~ ',
            TokenType::NOT_EQUIVALENT => ' !~ ',
            TokenType::GREATER_THAN   => ' > ',
            TokenType::LESS_THAN      => ' < ',
            TokenType::GREATER_EQUAL  => ' >= ',
            TokenType::LESS_EQUAL     => ' <= ',
            TokenType::PLUS           => ' + ',
            TokenType::MINUS          => ' - ',
            TokenType::MULTIPLY       => ' * ',
            TokenType::DIVIDE         => ' / ',
            TokenType::DIV            => ' div ',
            TokenType::MOD            => ' mod ',
            TokenType::PIPE           => ' | ',
            TokenType::AMPERSAND      => ' & ',
            TokenType::IN             => ' in ',
            TokenType::CONTAINS       => ' contains ',
            default                   => ' ' . $this->operator->value . ' ',
        };

        return '(' . $this->left->toString() . $operatorStr . $this->right->toString() . ')';
    }
}
