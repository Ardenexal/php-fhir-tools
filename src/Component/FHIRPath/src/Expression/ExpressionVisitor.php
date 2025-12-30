<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Expression;

/**
 * Visitor interface for traversing and processing the AST.
 *
 * Implementations of this interface can traverse the Abstract Syntax Tree
 * and perform operations on different node types without modifying the nodes.
 *
 * @author FHIR Tools Contributors
 */
interface ExpressionVisitor
{
    /**
     * Visit a literal node.
     *
     * @param LiteralNode $node The literal node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitLiteral(LiteralNode $node): mixed;

    /**
     * Visit an identifier node.
     *
     * @param IdentifierNode $node The identifier node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitIdentifier(IdentifierNode $node): mixed;

    /**
     * Visit a binary operator node.
     *
     * @param BinaryOperatorNode $node The binary operator node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitBinaryOperator(BinaryOperatorNode $node): mixed;

    /**
     * Visit a unary operator node.
     *
     * @param UnaryOperatorNode $node The unary operator node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitUnaryOperator(UnaryOperatorNode $node): mixed;

    /**
     * Visit a function call node.
     *
     * @param FunctionCallNode $node The function call node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitFunctionCall(FunctionCallNode $node): mixed;

    /**
     * Visit a member access node.
     *
     * @param MemberAccessNode $node The member access node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitMemberAccess(MemberAccessNode $node): mixed;

    /**
     * Visit an indexer node.
     *
     * @param IndexerNode $node The indexer node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitIndexer(IndexerNode $node): mixed;

    /**
     * Visit a type expression node (is/as).
     *
     * @param TypeExpressionNode $node The type expression node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitTypeExpression(TypeExpressionNode $node): mixed;

    /**
     * Visit an external constant node.
     *
     * @param ExternalConstantNode $node The external constant node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitExternalConstant(ExternalConstantNode $node): mixed;

    /**
     * Visit a collection literal node.
     *
     * @param CollectionLiteralNode $node The collection literal node to visit
     *
     * @return mixed The result of visiting the node
     */
    public function visitCollectionLiteral(CollectionLiteralNode $node): mixed;
}
