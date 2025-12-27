<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing an external constant.
 *
 * Represents external constants in FHIRPath that are prefixed with %.
 * For example, `%ucum` or `%context`.
 *
 * @author FHIR Tools Contributors
 */
class ExternalConstantNode extends ExpressionNode
{
    /**
     * Create a new external constant node.
     *
     * @param string $name   The constant name (without the % prefix)
     * @param int    $line   The line number
     * @param int    $column The column number
     */
    public function __construct(
        private readonly string $name,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the constant name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitExternalConstant($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return '%' . $this->name;
    }
}
