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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

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

    /**
     * Cache of FHIR class name → resolved resource type string (or null if not a resource).
     * Avoids repeated reflection over the class hierarchy within a single evaluator instance.
     *
     * @var array<string, string|null>
     */
    private array $resourceTypeCache = [];

    /**
     * Cache of class name → whether the class is a FHIR primitive wrapper.
     * Avoids repeated reflection within a single evaluator instance.
     *
     * @var array<string, bool>
     */
    private array $primitiveCache = [];

    private ?LoggerInterface $logger = null;

    /**
     * Base FHIR server URL used by resolve() for relative and canonical references.
     * e.g. "https://r4.smarthealthit.org"
     */
    private ?string $fhirServerUrl = null;

    /**
     * Terminology server URL used by memberOf() to call ValueSet/$validate-code.
     * Falls back to fhirServerUrl when not explicitly set.
     * e.g. "https://tx.fhir.org/r4"
     */
    private ?string $terminologyUrl = null;

    /**
     * PSR-18 HTTP client used by resolve() to fetch remote FHIR resources.
     */
    private ?ClientInterface $httpClient = null;

    /**
     * PSR-17 request factory used by resolve() to build GET requests.
     * Must be provided together with the HTTP client.
     */
    private ?RequestFactoryInterface $requestFactory = null;

    public function __construct()
    {
        $this->context      = new EvaluationContext();
        $this->typeResolver = new FHIRTypeResolver();
    }

    /**
     * Set a PSR-3 logger to receive trace() output.
     * When set, trace() writes via debug(). Otherwise falls back to error_log().
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Return the configured logger, or null if none is set.
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Set the base FHIR server URL used by resolve() when resolving relative
     * references (e.g. "Patient/123") and canonical search queries.
     *
     * Must not have a trailing slash — e.g. "https://r4.smarthealthit.org".
     */
    public function setFhirServerUrl(string $url): void
    {
        $this->fhirServerUrl = rtrim($url, '/');
    }

    /**
     * Return the configured FHIR server URL, or null if not set.
     */
    public function getFhirServerUrl(): ?string
    {
        return $this->fhirServerUrl;
    }

    /**
     * Set the terminology server URL used by memberOf() for ValueSet/$validate-code calls.
     * When not set, memberOf() falls back to the fhirServerUrl.
     *
     * Must not have a trailing slash — e.g. "https://tx.fhir.org/r4".
     */
    public function setTerminologyUrl(string $url): void
    {
        $this->terminologyUrl = rtrim($url, '/');
    }

    /**
     * Return the terminology server URL.
     * Falls back to fhirServerUrl if terminologyUrl was not explicitly set.
     */
    public function getTerminologyUrl(): ?string
    {
        return $this->terminologyUrl ?? $this->fhirServerUrl;
    }

    /**
     * Set the PSR-18 HTTP client and PSR-17 request factory used by resolve()
     * to fetch remote FHIR resources.
     *
     * Both are required together: the factory creates GET requests, the client
     * sends them. Any PSR-18 compatible client works (Guzzle, Symfony HttpClient
     * via Psr18Client, etc.).
     *
     * Example:
     *   use Symfony\Component\HttpClient\Psr18Client;
     *   $psr18 = new Psr18Client();
     *   $evaluator->setHttpClient($psr18, $psr18);
     */
    public function setHttpClient(ClientInterface $client, RequestFactoryInterface $requestFactory): void
    {
        $this->httpClient     = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Return the configured PSR-18 HTTP client, or null if not set.
     */
    public function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Return the configured PSR-17 request factory, or null if not set.
     */
    public function getRequestFactory(): ?RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    /**
     * Callable used by conformsTo() to validate a resource against a StructureDefinition profile.
     * Receives the resource item (array or object) and the canonical URL, and must return bool.
     *
     * @var (callable(mixed, string): bool)|null
     */
    private $conformsToValidator;

    /**
     * Set a callable that validates a FHIR resource against a profile.
     *
     * The callable receives the resource item (array or object) and the canonical
     * StructureDefinition URL, and must return true if the resource conforms.
     *
     * Example:
     *   $evaluator->setConformsToValidator(function (mixed $resource, string $url): bool {
     *       return myValidator->validate($resource, $url);
     *   });
     *
     * @param callable(mixed, string): bool $validator
     */
    public function setConformsToValidator(callable $validator): void
    {
        $this->conformsToValidator = $validator;
    }

    /**
     * Return the configured conformsTo validator callable, or null if not set.
     *
     * @return (callable(mixed, string): bool)|null
     */
    public function getConformsToValidator(): ?callable
    {
        return $this->conformsToValidator;
    }

    /**
     * Return the type resolver used by this evaluator.
     * Exposed so that functions (e.g. ofType) can reuse the same resolver
     * instance rather than creating their own.
     */
    public function getTypeResolver(): FHIRTypeResolver
    {
        return $this->typeResolver;
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
     * Evaluate an expression with a specific context, saving and restoring the current context.
     *
     * Used by functions that need per-item context evaluation (e.g. repeat(), select()).
     * Follows the same save/restore pattern used internally by visitMemberAccess().
     *
     * @param ExpressionNode    $expression The expression to evaluate
     * @param EvaluationContext $context    The context to use during evaluation
     */
    public function evaluateWithContext(ExpressionNode $expression, EvaluationContext $context): Collection
    {
        $savedContext  = $this->context;
        $this->context = $context;

        try {
            return $expression->accept($this);
        } finally {
            $this->context = $savedContext;
        }
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

        // FHIRPath spec: a resource type identifier (e.g. `Patient` in `Patient.name.given`)
        // acts as a type filter — it returns the current node when the node's resourceType
        // matches, and returns empty when it does not. This must be checked before falling
        // through to property navigation so that `Patient.name.given` does not mistakenly
        // look for a property key named "Patient".
        if ($this->matchesResourceType($currentNode, $name)) {
            return Collection::single($currentNode);
        }

        return $this->navigateProperty($currentNode, $name);
    }

    /**
     * Visit a member access node (dot notation)
     *
     * Function calls receive the full focus collection as their input (FHIRPath spec §5).
     * Property navigation operates per-item (standard member-access semantics).
     */
    public function visitMemberAccess(MemberAccessNode $node): Collection
    {
        // Evaluate the object expression
        $objectResult = $node->getObject()->accept($this);

        // Function calls: pass the whole collection (even if empty) as the function's input.
        // Each function is responsible for its own empty-input behaviour per the FHIRPath spec.
        // e.g. isDistinct({}) → true, exists({}) → false, count({}) → 0, not({}) → {}
        if ($node->getMember() instanceof FunctionCallNode) {
            $oldContext    = $this->context;
            $this->context = $this->context->withCollectionInput($objectResult);

            try {
                return $node->getMember()->accept($this);
            } finally {
                $this->context = $oldContext;
            }
        }

        // Property navigation: empty input propagates as empty (cannot navigate a property of nothing)
        if ($objectResult->isEmpty()) {
            return Collection::empty();
        }

        // Property navigation: iterate per-item and flatten results
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
        // When called via visitMemberAccess on a function call, collectionInput holds the
        // full focus collection. Consume it so it doesn't leak to nested evaluations.
        $collectionInput = $this->context->getCollectionInput();
        if ($collectionInput !== null) {
            $inputCollection   = $collectionInput;
            $this->context     = $this->context->withCollectionInput(null);
        } else {
            // Called directly (not via member access) — use the current node
            $input           = $this->context->getCurrentNode();
            $inputCollection = $input !== null ? $this->wrapValue($input) : Collection::empty();
        }

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

            // Membership operators
            TokenType::IN       => $this->evaluateMembership($left, $right),
            TokenType::CONTAINS => $this->evaluateMembership($right, $left),

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
        // Normalise namespace-qualified type names: System.Boolean → boolean, FHIR.Patient → Patient
        $typeName = $this->typeResolver->normalizeTypeName($node->getTypeName());
        $operator = $node->getOperator();

        // Handle 'is' operator — FHIRPath spec: returns a single boolean, not a filtered collection.
        // Empty input → empty; single item → bool result; multi-item → error.
        if ($operator === TokenType::IS) {
            if ($collection->isEmpty()) {
                return Collection::empty();
            }

            if (!$collection->isSingle()) {
                throw new EvaluationException("'is' operator requires a single-item collection", $node->getLine(), $node->getColumn());
            }

            return Collection::single($this->typeResolver->isOfType($collection->first(), $typeName));
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
     * Check whether a node's resourceType matches the given type name.
     *
     * Handles plain PHP arrays (json_decode assoc=true), typed PHP objects
     * with getResourceType()/resourceType, and typed model classes annotated
     * with a #[FhirResource(type: '...')] PHP attribute.
     */
    private function matchesResourceType(mixed $node, string $typeName): bool
    {
        if (is_array($node)) {
            return isset($node['resourceType']) && $node['resourceType'] === $typeName;
        }

        if (is_object($node)) {
            if (method_exists($node, 'getResourceType')) {
                return $node->getResourceType() === $typeName;
            }

            if (property_exists($node, 'resourceType')) {
                return $node->resourceType === $typeName;
            }

            // Check #[FhirResource(type: '...')] PHP attribute — no import of CodeGeneration
            // classes needed; detection is purely by attribute name string match via reflection.
            $class = get_class($node);
            if (!array_key_exists($class, $this->resourceTypeCache)) {
                $this->resourceTypeCache[$class] = $this->resolveResourceTypeFromAttribute($class);
            }

            $cachedType = $this->resourceTypeCache[$class];
            if ($cachedType !== null) {
                return $cachedType === $typeName;
            }
        }

        return false;
    }

    /**
     * Resolve the FHIR resource type from a #[FhirResource(type: '...')] attribute.
     *
     * Only inspects the class's own attributes (not parents), since resource classes
     * always carry the attribute directly. Returns null when not found.
     */
    private function resolveResourceTypeFromAttribute(string $class): ?string
    {
        if (!class_exists($class)) {
            return null;
        }

        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getAttributes() as $attribute) {
            if (str_ends_with($attribute->getName(), 'FhirResource')) {
                $args = $attribute->getArguments();
                $type = $args['type'] ?? $args[0] ?? null;

                return is_string($type) ? $type : null;
            }
        }

        return null;
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
     * Wrap a value in a collection, normalising FHIR-specific types along the way.
     *
     * For list arrays each element is passed through normalizeValue() so that FHIR
     * primitive wrappers and BackedEnums are reduced to their scalar PHP equivalents
     * before being added to the collection.
     */
    private function wrapValue(mixed $value): Collection
    {
        if ($value === null) {
            return Collection::empty();
        }

        if (is_array($value)) {
            // Check if it's an associative array (object) or indexed array (collection)
            if (array_is_list($value)) {
                return Collection::from(array_map($this->normalizeValue(...), $value));
            }

            return Collection::single($value);
        }

        return Collection::single($this->normalizeValue($value));
    }

    /**
     * Reduce a FHIR-specific value to its PHP scalar equivalent where possible.
     *
     * - BackedEnum → scalar value (e.g. NameUse::Official → 'official')
     * - FHIR primitive wrapper with #[FHIRPrimitive] attribute → ->value property
     * - All other types are returned unchanged (complex types, scalars, etc.)
     */
    private function normalizeValue(mixed $value): mixed
    {
        // BackedEnum → scalar (e.g. PHP 8.1+ enums used in some model fields)
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if (!is_object($value)) {
            return $value;
        }

        // FHIR primitive wrapper (classes annotated with #[FHIRPrimitive] on themselves
        // or any ancestor) → unwrap ->value so comparisons against string literals work.
        $class = get_class($value);
        if (!array_key_exists($class, $this->primitiveCache)) {
            $this->primitiveCache[$class] = $this->isFhirPrimitive($class);
        }

        if ($this->primitiveCache[$class] && property_exists($value, 'value')) {
            return $value->value; // e.g. StringPrimitive->value = 'Peter'
        }

        return $value;
    }

    /**
     * Return true when $class or any of its ancestors carries a #[FHIRPrimitive] attribute.
     *
     * Walking the hierarchy is necessary because code-type wrappers (e.g. NameUseType,
     * AdministrativeGenderType) extend CodePrimitive which carries the attribute directly,
     * rather than redeclaring it on every subclass.
     */
    private function isFhirPrimitive(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new \ReflectionClass($class);
        do {
            foreach ($reflection->getAttributes() as $attribute) {
                if (str_ends_with($attribute->getName(), 'FHIRPrimitive')) {
                    return true;
                }
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection !== false);

        return false;
    }

    /**
     * Evaluate membership: checks whether $needle (single item) is in $haystack.
     *
     * FHIRPath semantics:
     * - Empty needle → empty result
     * - Needle with more than one item → error
     * - Needle found in haystack → true; otherwise → false
     */
    private function evaluateMembership(Collection $needle, Collection $haystack): Collection
    {
        if ($needle->isEmpty()) {
            return Collection::empty();
        }

        if ($needle->count() > 1) {
            throw new EvaluationException("'in' operator requires a single item on the left side");
        }

        $needleValue = $needle->first();

        foreach ($haystack as $item) {
            // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators
            if ($item == $needleValue) {
                return Collection::single(true);
            }
        }

        return Collection::single(false);
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
