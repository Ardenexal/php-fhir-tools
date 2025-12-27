<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;

/**
 * AST node representing a literal value.
 *
 * Represents literal values in FHIRPath expressions such as strings, numbers,
 * booleans, null, dates, times, and quantities.
 *
 * @author FHIR Tools Contributors
 */
class LiteralNode extends ExpressionNode
{
    /**
     * Create a new literal node.
     *
     * @param mixed     $value  The literal value
     * @param TokenType $type   The type of literal
     * @param int       $line   The line number
     * @param int       $column The column number
     */
    public function __construct(
        private readonly mixed $value,
        private readonly TokenType $type,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the literal value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Get the literal type.
     */
    public function getType(): TokenType
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitLiteral($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return match ($this->type) {
            TokenType::STRING  => "'" . addslashes((string) $this->value) . "'",
            TokenType::BOOLEAN => $this->value ? 'true' : 'false',
            TokenType::NULL    => 'null',
            default            => (string) $this->value,
        };
    }
}
