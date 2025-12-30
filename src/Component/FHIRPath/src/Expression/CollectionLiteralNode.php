<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing a collection literal.
 *
 * Represents explicit collection literals in FHIRPath using brace notation.
 * For example, `{}` (empty collection) or `{1, 2, 3}`.
 *
 * @author FHIR Tools Contributors
 */
class CollectionLiteralNode extends ExpressionNode
{
    /**
     * Create a new collection literal node.
     *
     * @param array<ExpressionNode> $elements The elements in the collection
     * @param int                   $line     The line number
     * @param int                   $column   The column number
     */
    public function __construct(
        private readonly array $elements,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the collection elements.
     *
     * @return array<ExpressionNode>
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitCollectionLiteral($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        if (empty($this->elements)) {
            return '{}';
        }

        $elements = array_map(fn (ExpressionNode $e) => $e->toString(), $this->elements);

        return '{' . implode(', ', $elements) . '}';
    }
}
