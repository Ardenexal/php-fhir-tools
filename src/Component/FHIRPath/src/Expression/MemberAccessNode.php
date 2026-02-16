<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * AST node representing member access (dot notation).
 *
 * Represents accessing a member or property of an expression in FHIRPath.
 * For example, `Patient.name` where `name` is accessed on `Patient`.
 *
 * @author FHIR Tools Contributors
 */
class MemberAccessNode extends ExpressionNode
{
    /**
     * Create a new member access node.
     *
     * @param ExpressionNode $object The object/expression to access
     * @param ExpressionNode $member The member being accessed
     * @param int            $line   The line number
     * @param int            $column The column number
     */
    public function __construct(
        private readonly ExpressionNode $object,
        private readonly ExpressionNode $member,
        int $line,
        int $column
    ) {
        parent::__construct($line, $column);
    }

    /**
     * Get the object being accessed.
     */
    public function getObject(): ExpressionNode
    {
        return $this->object;
    }

    /**
     * Get the member being accessed.
     */
    public function getMember(): ExpressionNode
    {
        return $this->member;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(ExpressionVisitor $visitor): mixed
    {
        return $visitor->visitMemberAccess($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->object->toString() . '.' . $this->member->toString();
    }
}
