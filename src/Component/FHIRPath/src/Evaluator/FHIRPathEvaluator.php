<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Expression\BinaryOperatorNode;
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
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypeResolver;

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

    private FHIRTypeResolver $typeResolver;

    public function __construct()
    {
        $this->context      = new EvaluationContext();
        $this->typeResolver = new FHIRTypeResolver();
    }

    /**
     * Evaluate an expression against a resource
     *
     * @param ExpressionNode         $expression The parsed expression (AST)
     * @param mixed                  $resource   The FHIR resource or data to evaluate against
     * @param EvaluationContext|null $context    Optional evaluation context
     */
    public function evaluate(ExpressionNode $expression, mixed $resource, ?EvaluationContext $context = null): Collection
    {
        $this->context = $context ?? new EvaluationContext();
        $this->context->setRootResource($resource);
        $this->context->setVariable('this', $resource);
        $this->context->setEvaluator($this);

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
        return Collection::single($node->getValue());
    }

    /**
     * Visit an identifier node
     */
    public function visitIdentifier(IdentifierNode $node): Collection
    {
        $name = $node->getName();

        // Handle reserved identifiers ($this, $index, $total)
        if (str_starts_with($name, '$')) {
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
        $objectResult = $node->getObject()->accept($this);

        // If object result is empty, return empty (empty propagation)
        if ($objectResult->isEmpty()) {
            return Collection::empty();
        }

        // Navigate each item in the collection and flatten results
        $results = [];
        foreach ($objectResult as $item) {
            $oldContext    = $this->context;
            $this->context = $this->context->withCurrentNode($item);

            $memberResult = $node->getMember()->accept($this);
            $results      = [...$results, ...$memberResult->toArray()];

            $this->context = $oldContext;
        }

        return Collection::from($results);
    }

    /**
     * Visit a function call node
     */
    public function visitFunctionCall(FunctionCallNode $node): Collection
    {
        // Get the current collection (the input to the function)
        $input           = $this->context->getCurrentNode();
        $inputCollection = $input !== null ? $this->wrapValue($input) : Collection::empty();

        // Get the function from the registry
        $registry = FunctionRegistry::getInstance();

        if (!$registry->has($node->getName())) {
            throw new EvaluationException("Unknown function: {$node->getName()}", $node->getLine(), $node->getColumn());
        }

        $function = $registry->get($node->getName());

        // Evaluate parameters - they can be expressions or literals
        $evaluatedParams = [];
        foreach ($node->getParameters() as $param) {
            // Parameters are expression nodes that can be passed as-is to functions
            // Functions like where(), exists(), all() will evaluate them themselves
            $evaluatedParams[] = $param;
        }

        // Execute the function
        return $function->execute($inputCollection, $evaluatedParams, $this->context);
    }

    /**
     * Visit a binary operator node
     */
    public function visitBinaryOperator(BinaryOperatorNode $node): Collection
    {
        $left  = $node->getLeft()->accept($this);
        $right = $node->getRight()->accept($this);

        return match ($node->getOperator()) {
            // Union operator
            TokenType::PIPE => $left->union($right),

            // Arithmetic operators (require single values)
            TokenType::PLUS     => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a + $b),
            TokenType::MINUS    => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a - $b),
            TokenType::MULTIPLY => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a * $b),
            TokenType::DIVIDE   => $this->evaluateArithmetic($left, $right, fn ($a, $b) => (float) ($a / $b)),
            TokenType::DIV      => $this->evaluateArithmetic($left, $right, fn ($a, $b) => intdiv((int) $a, (int) $b)),
            TokenType::MOD      => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a % $b),

            // Comparison operators
            TokenType::EQUALS        => $this->evaluateComparison($left, $right, fn ($a, $b) => $a == $b),
            TokenType::NOT_EQUALS    => $this->evaluateComparison($left, $right, fn ($a, $b) => $a != $b),
            TokenType::LESS_THAN     => $this->evaluateComparison($left, $right, fn ($a, $b) => $a < $b),
            TokenType::GREATER_THAN  => $this->evaluateComparison($left, $right, fn ($a, $b) => $a > $b),
            TokenType::LESS_EQUAL    => $this->evaluateComparison($left, $right, fn ($a, $b) => $a <= $b),
            TokenType::GREATER_EQUAL => $this->evaluateComparison($left, $right, fn ($a, $b) => $a >= $b),

            // String concatenation
            TokenType::AMPERSAND => $this->evaluateStringConcat($left, $right),

            // Logical operators
            TokenType::AND     => $this->evaluateLogicalAnd($left, $right),
            TokenType::OR      => $this->evaluateLogicalOr($left, $right),
            TokenType::XOR     => $this->evaluateLogicalXor($left, $right),
            TokenType::IMPLIES => $this->evaluateImplies($left, $right),

            // Membership operators (to be fully implemented later)
            TokenType::IN, TokenType::CONTAINS => throw new EvaluationException("Operator '{$node->getOperator()->value}' not yet fully implemented", $node->getLine(), $node->getColumn()),

            default => throw new EvaluationException("Unknown operator: {$node->getOperator()->value}", $node->getLine(), $node->getColumn()),
        };
    }

    /**
     * Visit a unary operator node
     */
    public function visitUnaryOperator(UnaryOperatorNode $node): Collection
    {
        $operand = $node->getOperand()->accept($this);

        if ($operand->isEmpty()) {
            return Collection::empty();
        }

        if (!$operand->isSingle()) {
            throw new EvaluationException('Unary operator requires a single value', $node->getLine(), $node->getColumn());
        }

        $value = $operand->first();

        return match ($node->getOperator()) {
            TokenType::MINUS => Collection::single(-$value),
            TokenType::PLUS  => Collection::single(+$value),
            default          => throw new EvaluationException("Unknown unary operator: {$node->getOperator()->value}", $node->getLine(), $node->getColumn()),
        };
    }

    /**
     * Visit an indexer node
     */
    public function visitIndexer(IndexerNode $node): Collection
    {
        $collection  = $node->getCollection()->accept($this);
        $indexResult = $node->getIndex()->accept($this);

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
        // Evaluate the expression to get the collection to check/cast
        $collection = $node->getExpression()->accept($this);
        $typeName   = $node->getTypeName();
        $operator   = $node->getOperator();

        // Handle 'is' operator - type checking
        if ($operator === TokenType::IS) {
            // For collections, filter items that match the type
            $filtered = [];
            foreach ($collection->toArray() as $item) {
                if ($this->typeResolver->isOfType($item, $typeName)) {
                    $filtered[] = $item;
                }
            }

            return Collection::from($filtered);
        }

        // Handle 'as' operator - type casting
        if ($operator === TokenType::AS) {
            // For collections, cast each item to the target type
            $casted = [];
            foreach ($collection->toArray() as $item) {
                try {
                    $casted[] = $this->typeResolver->castToType($item, $typeName);
                } catch (\InvalidArgumentException $e) {
                    // If cast fails, skip the item (FHIRPath semantics)
                    continue;
                }
            }

            return Collection::from($casted);
        }

        throw new EvaluationException(sprintf('Unsupported type operator: %s', $operator->name), $node->getLine(), $node->getColumn());
    }

    /**
     * Visit an external constant node
     */
    public function visitExternalConstant(ExternalConstantNode $node): Collection
    {
        if ($this->context->hasExternalConstant($node->getName())) {
            $value = $this->context->getExternalConstant($node->getName());

            return $value !== null ? Collection::single($value) : Collection::empty();
        }

        throw new EvaluationException("External constant '%{$node->getName()}' not found", $node->getLine(), $node->getColumn());
    }

    /**
     * Visit a collection literal node
     */
    public function visitCollectionLiteral(CollectionLiteralNode $node): Collection
    {
        if (empty($node->getElements())) {
            return Collection::empty();
        }

        $items = [];
        foreach ($node->getElements() as $itemNode) {
            $result = $itemNode->accept($this);
            $items  = [...$items, ...$result->toArray()];
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
                try {
                    $value = $node->$propertyName;

                    return $this->wrapValue($value);
                } catch (\Error $e) {
                    // Property exists but is not accessible (e.g., private/protected)
                    // Fall through to try getter method
                }
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
            throw new EvaluationException('Arithmetic operators require single values');
        }

        $leftValue  = $left->first();
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

        $result = (string) $left->first() . (string) $right->first();

        return Collection::single($result);
    }

    /**
     * Evaluate logical AND (three-valued logic)
     */
    private function evaluateLogicalAnd(Collection $left, Collection $right): Collection
    {
        $leftBool  = $this->toBoolean($left);
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
        $leftBool  = $this->toBoolean($left);
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
        $leftBool  = $this->toBoolean($left);
        $rightBool = $this->toBoolean($right);

        if ($leftBool === null || $rightBool === null) {
            return Collection::empty();
        }

        $result = ($leftBool xor $rightBool);

        return Collection::single($result);
    }

    /**
     * Evaluate logical implication (A implies B = !A or B)
     */
    private function evaluateImplies(Collection $left, Collection $right): Collection
    {
        $leftBool  = $this->toBoolean($left);
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
