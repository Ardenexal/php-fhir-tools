<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

use Arden

exal\FHIRTools\Component\FHIRPath\Expression\BinaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\CollectionLiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionVisitor;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExternalConstantNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\FunctionCallNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IdentifierNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IndexerNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\LiteralNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\MemberAccessNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\TypeExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\UnaryOperatorNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath expression evaluator
 *
 * Evaluates parsed FHIRPath expressions (AST) against FHIR resources.
 * Implements the FHIRPath 2.0 specification including collection semantics,
 * empty propagation, and proper operator precedence.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class FHIRPathEvaluator implements ExpressionVisitor
{
    private EvaluationContext $context;

    public function __construct()
    {
        $this->context = new EvaluationContext();
    }

    /**
     * Evaluate an expression against a resource
     *
     * @param ExpressionNode $expression The parsed expression (AST)
     * @param mixed $resource The FHIR resource or data to evaluate against
     * @param EvaluationContext|null $context Optional evaluation context
     */
    public function evaluate(ExpressionNode $expression, mixed $resource, ?EvaluationContext $context = null): Collection
    {
        $this->context = $context ?? new EvaluationContext();
        $this->context->setRootResource($resource);
        $this->context->setVariable('this', $resource);
        
        if ($resource !== null) {
            $this->context->setCurrentNode($resource);
        }

        return $expression->accept($this);
    }

    /**
     * Visit a literal node
     */
    public function visitLiteral(LiteralNode $node): Collection
    {
        return Collection::single($node->value);
    }

    /**
     * Visit an identifier node
     */
    public function visitIdentifier(IdentifierNode $node): Collection
    {
        $name = $node->name;

        // Handle reserved identifiers ($this, $index, $total)
        if ($node->isReserved) {
            $variableName = ltrim($name, '$');
            if ($this->context->hasVariable($variableName)) {
                $value = $this->context->getVariable($variableName);
                return $value !== null ? Collection::single($value) : Collection::empty();
            }
            return Collection::empty();
        }

        // Navigate from current node
        $currentNode = $this->context->getCurrentNode();
        if ($currentNode === null) {
            return Collection::empty();
        }

        return $this->navigateProperty($currentNode, $name);
    }

    /**
     * Visit a member access node (dot notation)
     */
    public function visitMemberAccess(MemberAccessNode $node): Collection
    {
        // Evaluate the object expression
        $objectResult = $node->object->accept($this);

        // If object result is empty, return empty (empty propagation)
        if ($objectResult->isEmpty()) {
            return Collection::empty();
        }

        // Navigate each item in the collection and flatten results
        $results = [];
        foreach ($objectResult as $item) {
            $oldContext = $this->context;
            $this->context = $this->context->withCurrentNode($item);
            
            $memberResult = $node->member->accept($this);
            $results = [...$results, ...$memberResult->toArray()];
            
            $this->context = $oldContext;
        }

        return Collection::from($results);
    }

    /**
     * Visit a function call node
     */
    public function visitFunctionCall(FunctionCallNode $node): Collection
    {
        // Function dispatch will be implemented in Phase 5
        throw new EvaluationException(
            "Function '{$node->name}' not yet implemented",
            $node->line,
            $node->column
        );
    }

    /**
     * Visit a binary operator node
     */
    public function visitBinaryOperator(BinaryOperatorNode $node): Collection
    {
        $left = $node->left->accept($this);
        $right = $node->right->accept($this);

        return match ($node->operator) {
            // Union operator
            '|' => $left->union($right),
            
            // Arithmetic operators (require single values)
            '+' => $this->evaluateArithmetic($left, $right, fn($a, $b) => $a + $b),
            '-' => $this->evaluateArithmetic($left, $right, fn($a, $b) => $a - $b),
            '*' => $this->evaluateArithmetic($left, $right, fn($a, $b) => $a * $b),
            '/' => $this->evaluateArithmetic($left, $right, fn($a, $b) => $a / $b),
            'div' => $this->evaluateArithmetic($left, $right, fn($a, $b) => intdiv((int)$a, (int)$b)),
            'mod' => $this->evaluateArithmetic($left, $right, fn($a, $b) => $a % $b),
            
            // Comparison operators
            '=' => $this->evaluateComparison($left, $right, fn($a, $b) => $a == $b),
            '!=' => $this->evaluateComparison($left, $right, fn($a, $b) => $a != $b),
            '<' => $this->evaluateComparison($left, $right, fn($a, $b) => $a < $b),
            '>' => $this->evaluateComparison($left, $right, fn($a, $b) => $a > $b),
            '<=' => $this->evaluateComparison($left, $right, fn($a, $b) => $a <= $b),
            '>=' => $this->evaluateComparison($left, $right, fn($a, $b) => $a >= $b),
            
            // String concatenation
            '&' => $this->evaluateStringConcat($left, $right),
            
            // Logical operators
            'and' => $this->evaluateLogicalAnd($left, $right),
            'or' => $this->evaluateLogicalOr($left, $right),
            'xor' => $this->evaluateLogicalXor($left, $right),
            'implies' => $this->evaluateImplies($left, $right),
            
            // Membership operators (to be fully implemented later)
            'in', 'contains' => throw new EvaluationException(
                "Operator '{$node->operator}' not yet fully implemented",
                $node->line,
                $node->column
            ),
            
            default => throw new EvaluationException(
                "Unknown operator: {$node->operator}",
                $node->line,
                $node->column
            ),
        };
    }

    /**
     * Visit a unary operator node
     */
    public function visitUnaryOperator(UnaryOperatorNode $node): Collection
    {
        $operand = $node->operand->accept($this);

        if ($operand->isEmpty()) {
            return Collection::empty();
        }

        if (!$operand->isSingle()) {
            throw new EvaluationException(
                "Unary operator requires a single value",
                $node->line,
                $node->column
            );
        }

        $value = $operand->first();

        return match ($node->operator) {
            '-' => Collection::single(-$value),
            '+' => Collection::single(+$value),
            default => throw new EvaluationException(
                "Unknown unary operator: {$node->operator}",
                $node->line,
                $node->column
            ),
        };
    }

    /**
     * Visit an indexer node
     */
    public function visitIndexer(IndexerNode $node): Collection
    {
        $collection = $node->collection->accept($this);
        $indexResult = $node->index->accept($this);

        if ($indexResult->isEmpty() || !$indexResult->isSingle()) {
            return Collection::empty();
        }

        $index = $indexResult->first();
        if (!is_int($index)) {
            return Collection::empty();
        }

        $item = $collection->get($index);
        return $item !== null ? Collection::single($item) : Collection::empty();
    }

    /**
     * Visit a type expression node
     */
    public function visitTypeExpression(TypeExpressionNode $node): Collection
    {
        // Type operations will be fully implemented in Phase 7
        throw new EvaluationException(
            "Type operations not yet fully implemented",
            $node->line,
            $node->column
        );
    }

    /**
     * Visit an external constant node
     */
    public function visitExternalConstant(ExternalConstantNode $node): Collection
    {
        if ($this->context->hasExternalConstant($node->name)) {
            $value = $this->context->getExternalConstant($node->name);
            return $value !== null ? Collection::single($value) : Collection::empty();
        }

        throw new EvaluationException(
            "External constant '%{$node->name}' not found",
            $node->line,
            $node->column
        );
    }

    /**
     * Visit a collection literal node
     */
    public function visitCollectionLiteral(CollectionLiteralNode $node): Collection
    {
        if (empty($node->items)) {
            return Collection::empty();
        }

        $items = [];
        foreach ($node->items as $itemNode) {
            $result = $itemNode->accept($this);
            $items = [...$items, ...$result->toArray()];
        }

        return Collection::from($items);
    }

    /**
     * Navigate a property on a node
     */
    private function navigateProperty(mixed $node, string $propertyName): Collection
    {
        // Handle arrays
        if (is_array($node)) {
            if (array_key_exists($propertyName, $node)) {
                $value = $node[$propertyName];
                return $this->wrapValue($value);
            }
            return Collection::empty();
        }

        // Handle objects
        if (is_object($node)) {
            // Try direct property access
            if (property_exists($node, $propertyName)) {
                $value = $node->$propertyName;
                return $this->wrapValue($value);
            }

            // Try getter method
            $getter = 'get' . ucfirst($propertyName);
            if (method_exists($node, $getter)) {
                $value = $node->$getter();
                return $this->wrapValue($value);
            }

            // Handle polymorphic properties (value[x])
            // Look for properties like valueString, valueInteger, etc.
            foreach (get_object_vars($node) as $prop => $value) {
                if (str_starts_with($prop, $propertyName) && $prop !== $propertyName) {
                    return $this->wrapValue($value);
                }
            }

            return Collection::empty();
        }

        return Collection::empty();
    }

    /**
     * Wrap a value in a collection
     */
    private function wrapValue(mixed $value): Collection
    {
        if ($value === null) {
            return Collection::empty();
        }

        if (is_array($value)) {
            // Check if it's an associative array (object) or indexed array (collection)
            if (array_is_list($value)) {
                return Collection::from($value);
            }
            return Collection::single($value);
        }

        return Collection::single($value);
    }

    /**
     * Evaluate arithmetic operation
     *
     * @param callable(mixed, mixed): mixed $operation
     */
    private function evaluateArithmetic(Collection $left, Collection $right, callable $operation): Collection
    {
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        if (!$left->isSingle() || !$right->isSingle()) {
            throw new EvaluationException("Arithmetic operators require single values");
        }

        $leftValue = $left->first();
        $rightValue = $right->first();

        if (!is_numeric($leftValue) || !is_numeric($rightValue)) {
            return Collection::empty();
        }

        return Collection::single($operation($leftValue, $rightValue));
    }

    /**
     * Evaluate comparison operation
     *
     * @param callable(mixed, mixed): bool $operation
     */
    private function evaluateComparison(Collection $left, Collection $right, callable $operation): Collection
    {
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        if (!$left->isSingle() || !$right->isSingle()) {
            return Collection::empty();
        }

        $result = $operation($left->first(), $right->first());
        return Collection::single($result);
    }

    /**
     * Evaluate string concatenation
     */
    private function evaluateStringConcat(Collection $left, Collection $right): Collection
    {
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        if (!$left->isSingle() || !$right->isSingle()) {
            return Collection::empty();
        }

        $result = (string)$left->first() . (string)$right->first();
        return Collection::single($result);
    }

    /**
     * Evaluate logical AND (three-valued logic)
     */
    private function evaluateLogicalAnd(Collection $left, Collection $right): Collection
    {
        $leftBool = $this->toBoolean($left);
        $rightBool = $this->toBoolean($right);

        // Three-valued logic: false and anything = false
        if ($leftBool === false || $rightBool === false) {
            return Collection::single(false);
        }

        // true and true = true
        if ($leftBool === true && $rightBool === true) {
            return Collection::single(true);
        }

        // Otherwise empty (unknown)
        return Collection::empty();
    }

    /**
     * Evaluate logical OR (three-valued logic)
     */
    private function evaluateLogicalOr(Collection $left, Collection $right): Collection
    {
        $leftBool = $this->toBoolean($left);
        $rightBool = $this->toBoolean($right);

        // Three-valued logic: true or anything = true
        if ($leftBool === true || $rightBool === true) {
            return Collection::single(true);
        }

        // false or false = false
        if ($leftBool === false && $rightBool === false) {
            return Collection::single(false);
        }

        // Otherwise empty (unknown)
        return Collection::empty();
    }

    /**
     * Evaluate logical XOR
     */
    private function evaluateLogicalXor(Collection $left, Collection $right): Collection
    {
        $leftBool = $this->toBoolean($left);
        $rightBool = $this->toBoolean($right);

        if ($leftBool === null || $rightBool === null) {
            return Collection::empty();
        }

        $result = $leftBool xor $rightBool;
        return Collection::single($result);
    }

    /**
     * Evaluate logical implication (A implies B = !A or B)
     */
    private function evaluateImplies(Collection $left, Collection $right): Collection
    {
        $leftBool = $this->toBoolean($left);
        $rightBool = $this->toBoolean($right);

        // Three-valued logic for implies
        // false implies anything = true
        if ($leftBool === false) {
            return Collection::single(true);
        }

        // true implies true = true
        if ($leftBool === true && $rightBool === true) {
            return Collection::single(true);
        }

        // true implies false = false
        if ($leftBool === true && $rightBool === false) {
            return Collection::single(false);
        }

        // Otherwise empty (unknown)
        return Collection::empty();
    }

    /**
     * Convert collection to boolean (three-valued logic)
     *
     * @return bool|null true, false, or null (unknown)
     */
    private function toBoolean(Collection $collection): ?bool
    {
        if ($collection->isEmpty()) {
            return null;
        }

        if (!$collection->isSingle()) {
            return null;
        }

        $value = $collection->first();

        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        // Other types can't be converted to boolean in FHIRPath
        return null;
    }
}
