<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing an identifier.
 *
 * Represents simple identifiers in FHIRPath expressions, such as property names
 * and variable names.
 *
 * @author FHIR Tools Contributors
 */
class IdentifierNode extends ExpressionNode
{
    /**
     * Create a new identifier node.
     *
     * @param string $name   The identifier name
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
     * Get the identifier name.
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
        return $visitor->visitIdentifier($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->name;
    }
}
