<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * Base abstract class for all AST nodes in FHIRPath expressions.
 *
 * All expression nodes in the Abstract Syntax Tree extend from this base class.
 * The class supports the Visitor pattern for traversing and processing the AST.
 *
 * @author FHIR Tools Contributors
 */
abstract class ExpressionNode
{
    /**
     * Create a new expression node.
     *
     * @param int $line   The line number where this expression appears
     * @param int $column The column number where this expression starts
     */
    public function __construct(
        protected int $line,
        protected int $column
    ) {
    }

    /**
     * Get the line number where this expression appears.
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * Get the column number where this expression starts.
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * Accept a visitor for traversal and processing of the AST.
     *
     * This method implements the Visitor pattern, allowing different
     * operations to be performed on the AST without modifying the node classes.
     *
     * @param ExpressionVisitor $visitor The visitor to accept
     *
     * @return mixed The result of the visitor's visit method
     */
    abstract public function accept(ExpressionVisitor $visitor): mixed;

    /**
     * Get a string representation of this expression for debugging.
     *
     * @return string A human-readable representation of the expression
     */
    abstract public function toString(): string;

    /**
     * Magic method to convert expression to string.
     *
     * @return string The expression's string representation
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
