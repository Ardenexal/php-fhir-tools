<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing an indexer expression.
 *
 * Represents accessing an element by index in a collection using bracket notation.
 * For example, `collection[0]` or `name[index]`.
 *
 * @author FHIR Tools Contributors
 */
class IndexerNode extends ExpressionNode
{
    /**
     * Create a new indexer node.
     *
     * @param ExpressionNode $collection The collection being indexed
     * @param ExpressionNode $index      The index expression
     * @param int            $line       The line number
     * @param int            $column     The column number
     */
    public function __construct(
        private readonly ExpressionNode $collection,
        private readonly ExpressionNode $index,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the collection being indexed.
     */
    public function getCollection(): ExpressionNode
    {
        return $this->collection;
    }

    /**
     * Get the index expression.
     */
    public function getIndex(): ExpressionNode
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitIndexer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->collection->toString() . '[' . $this->index->toString() . ']';
    }
}
