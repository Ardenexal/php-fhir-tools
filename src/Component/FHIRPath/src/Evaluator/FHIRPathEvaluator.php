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
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathSemanticException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\ToQuantityFunction;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\TokenType;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDate;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDateTime;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTime;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypeResolver;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypedCollection;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypedScalar;
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

    private ComparisonService $comparisonService;

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

    /**
     * Cache of FHIR type name → FHIR_PROPERTY_MAP keys from the matching PHP class.
     * Used by strict-mode property validation on typed-empty collections.
     * null = type not found (no loaded PHP class for it); array = known property names.
     *
     * @var array<string, list<string>|null>
     */
    private array $fhirTypePropertyCache = [];

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
        $this->context           = new EvaluationContext();
        $this->typeResolver      = new FHIRTypeResolver();
        $this->comparisonService = new ComparisonService($this);
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

    public function getComparisonService(): ComparisonService
    {
        return $this->comparisonService;
    }

    /**
     * Return the current evaluation context.
     * Exposed so that function implementations can inspect strict mode and other context state.
     */
    public function getContext(): EvaluationContext
    {
        return $this->context;
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

        $result = $expression->accept($this);

        // Normalize FHIR primitive wrapper objects to their scalar values in the final
        // output collection. Internal evaluation keeps wrappers for sub-element navigation
        // (e.g. DatePrimitive.extension), but callers expect plain PHP scalars.
        if ($result->isEmpty()) {
            return $result;
        }

        $normalized = [];
        foreach ($result as $item) {
            $normalized[] = $this->normalizeValue($item);
        }

        return Collection::from($normalized);
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
        if ($node->getType() === TokenType::QUANTITY) {
            $quantity = ToQuantityFunction::tryConvert($node->getValue());

            return $quantity !== null ? Collection::single($quantity) : Collection::single($node->getValue());
        }

        // Wrap date/time literals in typed value objects so that inferType() can distinguish
        // Date/DateTime/Time from plain PHP strings (e.g. resource property values).
        // The @ prefix is stripped so the bare ISO string matches resource property values.
        if ($node->getType() === TokenType::DATETIME || $node->getType() === TokenType::TIME) {
            $value = $node->getValue();
            if (is_string($value) && str_starts_with($value, '@')) {
                $bare = substr($value, 1);

                // Time-only literal: @T14, @T14:34:28, etc.
                if (str_starts_with($bare, 'T')) {
                    return Collection::single(new FHIRPathTime($bare));
                }

                // DateTime literal: @2015-02-04T, @2015-02-04T14:34:28+10:00, etc.
                if (str_contains($bare, 'T')) {
                    return Collection::single(new FHIRPathDateTime($bare));
                }

                // Date-only literal: @2015, @2015-02, @2015-02-04, etc.
                return Collection::single(new FHIRPathDate($bare));
            }
        }

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

                // Return the value as-is, including null: a variable explicitly set to null
                // (e.g. $this for a null FHIR primitive element in select()) is still a valid
                // node that hasValue() should evaluate to false for.
                return Collection::single($value);
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

        // Strict mode: if the identifier starts with an uppercase letter (indicating a
        // type-filter usage like `Encounter.name`), and the current node is a typed FHIR
        // resource, this is a wrong-context error — the type filter did not match.
        if ($this->context->isStrictMode()
            && isset($name[0]) && ctype_upper($name[0])
            && is_object($currentNode)
            && $this->resolveResourceTypeFromAttribute(get_class($currentNode)) !== null
        ) {
            throw new FHIRPathSemanticException("Type filter '{$name}' does not match the context resource type");
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
            // Strict mode: if the empty collection carries a declared FHIR type (from an `as` cast),
            // validate that the accessed property actually exists on that type.
            if ($this->context->isStrictMode()
                && $objectResult->getDeclaredType() !== null
                && $node->getMember() instanceof IdentifierNode
            ) {
                $this->assertPropertyExistsOnFhirType(
                    $objectResult->getDeclaredType(),
                    $node->getMember()->getName(),
                );
            }

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
        // Semantic validation: detect ambiguous type expressions in comparison operators
        // Per FHIRPath spec, expressions like "1 > 2 is Boolean" are invalid because
        // it's unclear whether it means "(1 > 2) is Boolean" or "1 > (2 is Boolean)"
        $operator = $node->getOperator();
        $left     = $node->getLeft()->accept($this);
        $right    = $node->getRight()->accept($this);

        return match ($operator) {
            // Union operator
            TokenType::PIPE => $left->union($right),

            // Arithmetic operators (require single values)
            TokenType::PLUS     => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a + $b, TokenType::PLUS),
            TokenType::MINUS    => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a - $b, TokenType::MINUS),
            TokenType::MULTIPLY => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a * $b, TokenType::MULTIPLY),
            TokenType::DIVIDE   => $this->evaluateArithmetic($left, $right, fn ($a, $b) => (float) ($a / $b), TokenType::DIVIDE),
            TokenType::DIV      => $this->evaluateArithmetic($left, $right, fn ($a, $b) => intdiv((int) $a, (int) $b), TokenType::DIV),
            TokenType::MOD      => $this->evaluateArithmetic($left, $right, fn ($a, $b) => $a % $b, TokenType::MOD),

            // Equality/equivalence operators (support collections)
            TokenType::EQUALS         => $this->comparisonService->compareEquality($left, $right, '='),
            TokenType::NOT_EQUALS     => $this->comparisonService->compareEquality($left, $right, '!='),
            TokenType::EQUIVALENT     => $this->comparisonService->compareEquality($left, $right, '~'),
            TokenType::NOT_EQUIVALENT => $this->comparisonService->compareEquality($left, $right, '!~'),

            // Ordering operators (require single values)
            TokenType::LESS_THAN     => $this->comparisonService->compareOrdering($left, $right, fn ($a, $b) => $a < $b),
            TokenType::GREATER_THAN  => $this->comparisonService->compareOrdering($left, $right, fn ($a, $b) => $a > $b),
            TokenType::LESS_EQUAL    => $this->comparisonService->compareOrdering($left, $right, fn ($a, $b) => $a <= $b),
            TokenType::GREATER_EQUAL => $this->comparisonService->compareOrdering($left, $right, fn ($a, $b) => $a >= $b),

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

        // Semantic validation: unary +/- operators require numeric or Quantity operands
        // Per FHIRPath spec, expressions like "-1.convertsToInteger()" are invalid
        // because they're ambiguous (-(1.convertsToInteger()) attempts to negate a boolean)
        $isQuantity = is_array($value) && array_key_exists('value', $value) && is_numeric($value['value']);
        if (!is_numeric($value) && !($value instanceof FHIRPathDecimal) && !$isQuantity) {
            throw new EvaluationException('Unary +/- operators require numeric operands', $node->getLine(), $node->getColumn());
        }

        if ($value instanceof FHIRPathDecimal) {
            return match ($node->getOperator()) {
                TokenType::MINUS => Collection::single(new FHIRPathDecimal(str_starts_with($value->value, '-') ? substr($value->value, 1) : '-' . $value->value)),
                TokenType::PLUS  => Collection::single($value),
                default          => throw new EvaluationException("Unknown unary operator: {$node->getOperator()->value}", $node->getLine(), $node->getColumn()),
            };
        }

        // Quantity: negate the numeric value, preserve unit
        if ($isQuantity) {
            /** @var array<string, mixed> $value */
            $result          = $value;
            $result['value'] = match ($node->getOperator()) {
                TokenType::MINUS => -(float) $value['value'],
                TokenType::PLUS  => +(float) $value['value'],
                default          => throw new EvaluationException("Unknown unary operator: {$node->getOperator()->value}", $node->getLine(), $node->getColumn()),
            };

            return Collection::single($result);
        }

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
        $operator   = $node->getOperator();

        // Handle 'is' operator — FHIRPath spec: returns a single boolean, not a filtered collection.
        // Empty input → empty; single item → bool result; multi-item → error.
        //
        // IMPORTANT: pass the raw (un-normalized) type name so that isOfType() can detect
        // the System.* namespace distinction before normalization. e.g. 'System.Boolean' and
        // 'Boolean' both normalize to 'boolean', but they refer to System namespace, not FHIR.
        if ($operator === TokenType::IS) {
            if ($collection->isEmpty()) {
                return Collection::empty();
            }

            if (!$collection->isSingle()) {
                throw new EvaluationException("'is' operator requires a single-item collection", $node->getLine(), $node->getColumn());
            }

            return Collection::single($this->typeResolver->isOfType($collection->first(), $node->getTypeName()));
        }

        // Normalise namespace-qualified type names: System.Boolean → boolean, FHIR.Patient → Patient
        $typeName = $this->typeResolver->normalizeTypeName($node->getTypeName());

        // Handle 'as' operator — strict type filter per FHIRPath spec §5.22
        // Rules:
        //   - Empty input  → empty
        //   - Unknown type → execution error
        //   - Multi-item   → execution error (both operator and function form)
        //   - Single item that matches the type (strict identity, no hierarchy) → return it
        //   - Single item that does not match → return empty
        if ($operator === TokenType::AS) {
            if ($collection->isEmpty()) {
                return Collection::empty();
            }

            // Unknown type specifiers are execution errors per the FHIRPath spec
            if (!$this->typeResolver->isKnownTypeName($node->getTypeName())) {
                throw new EvaluationException("'as' operator used with unknown type: '{$node->getTypeName()}'");
            }

            // Multi-item input is an execution error for both operator and function forms
            if (!$collection->isSingle()) {
                throw new EvaluationException(sprintf("'as' operator requires a single-item collection, got %d items", $collection->count()), $node->getLine(), $node->getColumn());
            }

            $item = $collection->first();

            if ($this->typeResolver->isOfType($item, $typeName, strict: true)) {
                return Collection::single($item);
            }

            return $this->context->isStrictMode()
                ? Collection::typedEmpty($typeName)
                : Collection::empty();
        }

        throw new EvaluationException(sprintf('Unsupported type operator: %s', $operator->name), $node->getLine(), $node->getColumn());
    }

    /**
     * Visit an external constant node
     */
    public function visitExternalConstant(ExternalConstantNode $node): Collection
    {
        $name = $node->getName();

        // User-supplied constants take priority over built-ins
        if ($this->context->hasExternalConstant($name)) {
            $value = $this->context->getExternalConstant($name);

            return $value !== null ? Collection::single($value) : Collection::empty();
        }

        // Spec-mandated environment variables
        $resolved = $this->resolveEnvironmentVariable($name);
        if ($resolved !== null) {
            return $resolved instanceof Collection ? $resolved : Collection::single($resolved);
        }

        throw new EvaluationException("External constant '%{$name}' not found", $node->getLine(), $node->getColumn());
    }

    /**
     * Resolve spec-mandated environment variables.
     *
     * Returns a string for well-known URL constants, a Collection for node
     * references (%context, %resource, %rootResource), or null if the name
     * is not a built-in variable.
     *
     * Spec references:
     *   - FHIRPath core spec §3.3: %ucum, %context
     *   - FHIR R4 FHIRPath supplement: %resource, %rootResource, %sct, %loinc,
     *     %vs-<name>, %ext-<name>
     *
     * Note: %resource differs from %rootResource when navigating contained
     * resources via resolve() — for now both return rootResource since contained
     * resource navigation is not yet implemented.
     */
    private function resolveEnvironmentVariable(string $name): Collection|string|null
    {
        // Node references
        if ($name === 'context' || $name === 'resource' || $name === 'rootResource') {
            $root = $this->context->getRootResource();

            return $root !== null ? Collection::single($root) : Collection::empty();
        }

        // Static URL constants
        static $known = [
            'ucum'  => 'http://unitsofmeasure.org',
            'sct'   => 'http://snomed.info/sct',
            'loinc' => 'http://loinc.org',
        ];

        if (array_key_exists($name, $known)) {
            return $known[$name];
        }

        // Dynamic ValueSet pattern: %vs-<name> → http://hl7.org/fhir/ValueSet/<name>
        if (str_starts_with($name, 'vs-')) {
            return 'http://hl7.org/fhir/ValueSet/' . substr($name, 3);
        }

        // Dynamic StructureDefinition pattern: %ext-<name> → http://hl7.org/fhir/StructureDefinition/<name>
        if (str_starts_with($name, 'ext-')) {
            return 'http://hl7.org/fhir/StructureDefinition/' . substr($name, 4);
        }

        return null;
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
     * Look up the FHIR type of a property on an object using its FHIR_PROPERTY_MAP constant.
     *
     * Returns the canonical FHIR type name (e.g. 'boolean', 'integer') only when the
     * property is a scalar FHIR primitive type (not a System.* URL type). Returns null
     * when the FHIR_PROPERTY_MAP is absent, the property is not in the map, the property
     * is not a scalar, or the fhirType is a FHIRPath System type URL.
     */
    private function resolveFhirPropertyType(object $node, string $propertyName): ?string
    {
        $class = get_class($node);
        if (!defined("{$class}::FHIR_PROPERTY_MAP")) {
            return null;
        }

        /** @var array<string, array{fhirType?: string, propertyKind?: string, variants?: array<int, array{fhirType?: string, propertyKind?: string, phpType?: string, jsonKey?: string, isBuiltin?: bool}>|null}> $map */
        $map = constant("{$class}::FHIR_PROPERTY_MAP");

        // Direct property lookup
        if (isset($map[$propertyName])) {
            $meta = $map[$propertyName];
            // Only wrap scalar properties with FHIR (not System.*) type names
            if (($meta['propertyKind'] ?? '') === 'scalar'
                && isset($meta['fhirType'])
                && !str_starts_with($meta['fhirType'], 'http://')
            ) {
                return $meta['fhirType'];
            }
        }

        // Check if this property is a variant of a choice property (e.g. 'valueBoolean' as part of 'value[x]')
        // Walk all entries looking for a choice property whose variants include this prop name (via jsonKey)
        foreach ($map as $propMeta) {
            if (empty($propMeta['variants'])) {
                continue;
            }

            foreach ($propMeta['variants'] as $variant) {
                $jsonKey = $variant['jsonKey'] ?? null;
                if ($jsonKey                            === $propertyName
                    && ($variant['propertyKind'] ?? '') === 'scalar'
                    && isset($variant['fhirType'])
                    && !str_starts_with($variant['fhirType'], 'http://')
                ) {
                    return $variant['fhirType'];
                }
            }
        }

        return null;
    }

    /**
     * Return the FHIR type name for a 'complex' or 'extension' kind property on an object.
     *
     * Complements resolveFhirPropertyType() which only handles 'scalar' properties.
     * Used to wrap array items in FHIRTypedCollection so that ofType() and is() can
     * identify raw arrays as their FHIR type (e.g. Patient.name items as HumanName).
     *
     * Returns null when the object has no FHIR_PROPERTY_MAP, the property is absent,
     * or the property kind is not 'complex' or 'extension'.
     */
    private function resolveComplexPropertyFhirType(object $node, string $propertyName): ?string
    {
        $class = get_class($node);
        if (!defined("{$class}::FHIR_PROPERTY_MAP")) {
            return null;
        }

        /** @var array<string, array{fhirType?: string, propertyKind?: string}> $map */
        $map = constant("{$class}::FHIR_PROPERTY_MAP");

        if (!isset($map[$propertyName])) {
            return null;
        }

        $meta = $map[$propertyName];
        $kind = $meta['propertyKind'] ?? '';

        if (!in_array($kind, ['complex', 'extension'], true)) {
            return null;
        }

        $fhirType = $meta['fhirType'] ?? null;

        if (
            !is_string($fhirType)
            || str_starts_with($fhirType, 'http://')
            || str_starts_with($fhirType, 'https://')
        ) {
            return null;
        }

        return $fhirType;
    }

    /**
     * Return true when $propertyName is a choice-type variant key in the class's FHIR_PROPERTY_MAP
     * (e.g. 'valueQuantity' is a variant of the 'value[x]' choice property on Observation),
     * rather than a first-class property.
     *
     * Used by strict-mode guards to reject direct variant access like `Observation.valueQuantity`.
     *
     * @param class-string $class
     */
    private function isChoiceVariantAccess(string $class, string $propertyName): bool
    {
        /** @var array<string, array{variants?: array<int, array{jsonKey?: string}>|null}> $map */
        $map = constant("{$class}::FHIR_PROPERTY_MAP");

        if (array_key_exists($propertyName, $map)) {
            return false; // It's a first-class property
        }

        foreach ($map as $meta) {
            foreach ($meta['variants'] ?? [] as $variant) {
                if (($variant['jsonKey'] ?? null) === $propertyName) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Assert that $propertyName is a valid property of the given FHIR type name in strict mode.
     *
     * Searches loaded PHP classes for one whose FHIR_PROPERTY_MAP matches $fhirType via a
     * #[FHIRComplexType] or #[FHIRPrimitive] attribute. If the class is found and the property
     * is not in the map, throws FHIRPathSemanticException. If no class is found (type not loaded),
     * silently returns to avoid false positives.
     *
     * Results are cached per type name.
     *
     * @throws FHIRPathSemanticException when the property is known to not exist on the type
     */
    private function assertPropertyExistsOnFhirType(string $fhirType, string $propertyName): void
    {
        if (!array_key_exists($fhirType, $this->fhirTypePropertyCache)) {
            $this->fhirTypePropertyCache[$fhirType] = null;

            foreach (get_declared_classes() as $class) {
                if (!defined("{$class}::FHIR_PROPERTY_MAP")) {
                    continue;
                }

                // Check for #[FHIRComplexType(typeName: '...')] or similar attribute
                if (!class_exists($class)) {
                    continue;
                }

                $reflection = new \ReflectionClass($class);

                $matched = false;
                foreach ($reflection->getAttributes() as $attribute) {
                    $attrName = $attribute->getName();
                    if (!str_ends_with($attrName, 'FHIRComplexType')
                        && !str_ends_with($attrName, 'FHIRPrimitive')
                        && !str_ends_with($attrName, 'FhirComplexType')
                        && !str_ends_with($attrName, 'FhirPrimitive')
                    ) {
                        continue;
                    }

                    $args = $attribute->getArguments();
                    $type = $args['typeName'] ?? $args['type'] ?? $args[0] ?? null;
                    if (is_string($type) && $type === $fhirType) {
                        $matched = true;
                        break;
                    }
                }

                if ($matched) {
                    /** @var array<string, mixed> $map */
                    $map                                    = constant("{$class}::FHIR_PROPERTY_MAP");
                    $this->fhirTypePropertyCache[$fhirType] = array_keys($map);
                    break;
                }
            }

            // If still not found, derive candidate namespaces from already-loaded FHIR classes
            // and attempt autoloading. This handles FHIR DataTypes that were never instantiated
            // (e.g. Period when the test data only contains a Quantity).
            if ($this->fhirTypePropertyCache[$fhirType] === null) {
                $namespacesToTry = [];
                foreach (get_declared_classes() as $declared) {
                    if (defined("{$declared}::FHIR_PROPERTY_MAP")) {
                        $lastSlash = strrpos($declared, '\\');
                        if ($lastSlash !== false) {
                            $namespacesToTry[substr($declared, 0, $lastSlash)] = true;
                        }
                    }
                }

                foreach (array_keys($namespacesToTry) as $ns) {
                    $candidate = $ns . '\\' . $fhirType;
                    if (!class_exists($candidate, true) || !defined("{$candidate}::FHIR_PROPERTY_MAP")) {
                        continue;
                    }
                    /** @var array<string, mixed> $candidateMap */
                    $candidateMap                           = constant("{$candidate}::FHIR_PROPERTY_MAP");
                    $this->fhirTypePropertyCache[$fhirType] = array_keys($candidateMap);
                    break;
                }
            }
        }

        $knownProperties = $this->fhirTypePropertyCache[$fhirType];
        if ($knownProperties !== null && !in_array($propertyName, $knownProperties, true)) {
            throw new FHIRPathSemanticException("Property '{$propertyName}' does not exist on FHIR type '{$fhirType}'");
        }
    }

    /**
     * Navigate a property on a node
     */
    private function navigateProperty(mixed $node, string $propertyName): Collection
    {
        // FHIRTypedCollection wraps a raw data array with FHIR type context.
        // Delegate navigation to the underlying array — type context is preserved by the
        // caller and does not need to be threaded through sub-property navigation.
        if ($node instanceof FHIRTypedCollection) {
            return $this->navigateProperty($node->value, $propertyName);
        }

        // Handle arrays
        if (is_array($node)) {
            if (array_key_exists($propertyName, $node)) {
                $value = $node[$propertyName];

                return $this->wrapValue($value);
            }

            // Polymorphic prefix matching for choice elements on raw FHIR data arrays.
            // e.g., looking for 'value' finds key 'valueString', 'valueUuid', etc.
            foreach ($node as $key => $rawValue) {
                if (!is_string($key) || !str_starts_with($key, $propertyName)) {
                    continue;
                }
                $suffix = substr($key, strlen($propertyName));
                if ($suffix === '' || !ctype_upper($suffix[0])) {
                    continue;
                }
                $fhirType = lcfirst($suffix); // 'String'→'string', 'Uuid'→'uuid', etc.
                // Unwrap XML-encoded primitive: ['@value' => scalar, '#' => ''] → scalar
                if (is_array($rawValue)) {
                    $otherKeys = array_diff(array_keys($rawValue), ['@value', '#']);
                    if (empty($otherKeys) && array_key_exists('@value', $rawValue)) {
                        $scalar = $rawValue['@value'];

                        return Collection::single(new FHIRTypedScalar($scalar, $fhirType));
                    }

                    // Complex type array (not a simple @value primitive): preserve FHIR type
                    // using the original capitalised suffix (e.g. 'Age', 'Quantity', 'HumanName')
                    // so that is() and ofType() can correctly resolve the FHIR type.
                    return Collection::single(new FHIRTypedCollection($rawValue, $suffix));
                }

                return $this->wrapValue($rawValue);
            }

            return Collection::empty();
        }

        // Handle objects
        if (is_object($node)) {
            // Strict mode: guard against direct access to a choice-type variant
            // (e.g. Observation.valueQuantity when 'value' is the choice property).
            // Only fires when the class has FHIR_PROPERTY_MAP (typed model).
            if ($this->context->isStrictMode()) {
                $class = get_class($node);
                if (defined("{$class}::FHIR_PROPERTY_MAP")
                    && $this->isChoiceVariantAccess($class, $propertyName)
                ) {
                    throw new FHIRPathSemanticException("Direct access to choice type variant '{$propertyName}' is not allowed in strict mode. Use 'value.ofType(Type)' or '(value as Type)' instead.");
                }
            }

            // Try direct property access
            if (property_exists($node, $propertyName)) {
                try {
                    $value = $node->$propertyName;

                    // Wrap PHP scalar values that have a known FHIR primitive type in FHIR_PROPERTY_MAP,
                    // so that type() and is() can distinguish FHIR.boolean from System.Boolean.
                    $fhirType = $this->resolveFhirPropertyType($node, $propertyName);
                    if ($fhirType !== null && is_scalar($value)) {
                        return Collection::single(new FHIRTypedScalar($value, $fhirType));
                    }

                    // Wrap complex-type array items with FHIR type context from the property map
                    // so that ofType() and is() can identify raw arrays as their FHIR type
                    // (e.g. Patient.name items as HumanName, Observation.extension as Extension).
                    $complexFhirType = $this->resolveComplexPropertyFhirType($node, $propertyName);
                    if ($complexFhirType !== null && is_array($value)) {
                        if (array_is_list($value)) {
                            $typedItems = array_map(
                                static fn (mixed $item) => is_array($item)
                                    ? new FHIRTypedCollection($item, $complexFhirType)
                                    : $item,
                                $value,
                            );

                            return Collection::from($typedItems);
                        }

                        // Single associative array stored non-list (e.g. one extension element)
                        return Collection::single(new FHIRTypedCollection($value, $complexFhirType));
                    }

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
                    // Look up FHIR type for the resolved variant property
                    $fhirType = $this->resolveFhirPropertyType($node, $prop);
                    if ($fhirType !== null && is_scalar($value)) {
                        return Collection::single(new FHIRTypedScalar($value, $fhirType));
                    }

                    return $this->wrapValue($value);
                }
            }

            // Strict mode: if the class has a FHIR_PROPERTY_MAP and nothing was found,
            // the property is semantically invalid for this FHIR type.
            if ($this->context->isStrictMode()) {
                $class = get_class($node);
                if (defined("{$class}::FHIR_PROPERTY_MAP")) {
                    throw new FHIRPathSemanticException("Property '{$propertyName}' does not exist on FHIR type");
                }
            }

            return Collection::empty();
        }

        return Collection::empty();
    }

    /**
     * Wrap a value in a collection, unwrapping FHIR primitive arrays and objects.
     *
     * Per FHIRPath specification, when accessing a property that contains a primitive
     * value, the evaluator should return the scalar value itself, not a wrapper object.
     * This method automatically unwraps:
     * - FHIR primitive arrays (those with '@value' key) to their scalar values
     * - FHIR primitive objects (those with #[FHIRPrimitive] attribute) to their ->value property
     *
     * For list arrays, each element is recursively unwrapped if it's a primitive array or object.
     */
    private function wrapValue(mixed $value): Collection
    {
        if ($value === null) {
            return Collection::empty();
        }

        if (is_array($value)) {
            // FHIR primitive wrapper (e.g., ['@value' => 'Peter']) → unwrap to scalar
            // This implements FHIRPath spec requirement that property access returns
            // the primitive value, not a wrapper object
            if (isset($value['@value']) && count($value) === 1) {
                return Collection::single($value['@value']);
            }

            // Check if it's an associative array (object) or indexed array (collection)
            if (array_is_list($value)) {
                // For collection items, unwrap @value arrays but keep FHIR primitive
                // wrapper objects as-is — sub-element access (e.g. .extension) and type
                // inference require the wrapper to be preserved. Consumers that need the
                // scalar call normalizeValue() themselves.
                $unwrapped = array_map(function($item) {
                    if (is_array($item) && isset($item['@value']) && count($item) === 1) {
                        return $item['@value'];
                    }

                    return $item;
                }, $value);

                return Collection::from($unwrapped);
            }

            return Collection::single($value);
        }

        // Keep FHIR primitive wrapper objects in the collection; BackedEnum values
        // (which have no sub-elements) are the only objects unwrapped eagerly here.
        // Every scalar-consuming function calls normalizeValue() on items it reads.
        if (is_object($value)) {
            if ($value instanceof \BackedEnum) {
                return Collection::single($value->value);
            }

            return Collection::single($value);
        }

        return Collection::single($value);
    }

    /**
     * Reduce a FHIR-specific value to its PHP scalar equivalent where possible.
     *
     * - BackedEnum → scalar value (e.g. NameUse::Official → 'official')
     * - FHIR primitive wrapper with #[FHIRPrimitive] attribute → ->value property
     * - All other types are returned unchanged (complex types, scalars, etc.)
     */
    public function normalizeValue(mixed $value): mixed
    {
        // FHIRTypedScalar → unwrap to PHP scalar (type context no longer needed in final output)
        if ($value instanceof FHIRTypedScalar) {
            return $value->value;
        }

        // FHIRTypedCollection → unwrap to raw array (type context used only by type checks)
        if ($value instanceof FHIRTypedCollection) {
            return $value->value;
        }

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

        // Normalize to unwrap FHIR primitive wrappers before comparison
        $needleValue = $this->normalizeValue($needle->first());

        foreach ($haystack as $item) {
            // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators
            if ($this->normalizeValue($item) == $needleValue) {
                return Collection::single(true);
            }
        }

        return Collection::single(false);
    }

    /**
     * Evaluate arithmetic operation
     *
     * @param callable(mixed, mixed): mixed $operation
     * @param TokenType                     $operator
     */
    private function evaluateArithmetic(Collection $left, Collection $right, callable $operation, TokenType $operator): Collection
    {
        if ($left->isEmpty() || $right->isEmpty()) {
            return Collection::empty();
        }

        if (!$left->isSingle() || !$right->isSingle()) {
            throw new EvaluationException('Arithmetic operators require single values');
        }

        // Try to extract quantities from both values
        $leftQty  = $this->comparisonService->tryExtractQuantity($left->first());
        $rightQty = $this->comparisonService->tryExtractQuantity($right->first());

        // If both are quantities, perform quantity arithmetic
        if ($leftQty !== null && $rightQty !== null) {
            return $this->performQuantityArithmetic($leftQty, $rightQty, $operation, $operator);
        }

        // Normalize values to handle FHIR primitives and enums
        $leftValue  = $this->normalizeValue($left->first());
        $rightValue = $this->normalizeValue($right->first());

        // Unwrap FHIRPath date/time literal wrappers to plain strings so the
        // is_string() check below and performDateArithmetic() receive bare ISO strings.
        if ($leftValue instanceof FHIRPathTemporalTypeInterface) {
            $leftValue = $leftValue->getValue();
        }

        if ($rightValue instanceof FHIRPathTemporalTypeInterface) {
            $rightValue = $rightValue->getValue();
        }

        // Date/DateTime arithmetic: date +/- quantity (duration)
        if (($operator === TokenType::PLUS || $operator === TokenType::MINUS)
            && is_string($leftValue)
            && $this->comparisonService->isDateTimeString($leftValue)
            && $rightQty !== null
        ) {
            return $this->performDateArithmetic($leftValue, $rightQty, $operator);
        }

        // String concatenation via + operator (per FHIRPath spec, + works on strings too)
        if (is_string($leftValue) && is_string($rightValue) && $operator === TokenType::PLUS) {
            return Collection::single($leftValue . $rightValue);
        }

        // bcmath arithmetic when either operand is a FHIRPathDecimal (exact precision),
        // or when the operator is DIVIDE (FHIRPath spec: division always yields Decimal).
        $leftIsDecimal  = $leftValue instanceof FHIRPathDecimal;
        $rightIsDecimal = $rightValue instanceof FHIRPathDecimal;

        if ($leftIsDecimal || $rightIsDecimal || $operator === TokenType::DIVIDE) {
            $leftStr  = $leftIsDecimal ? $leftValue->value : $this->numericToBcString($leftValue);
            $rightStr = $rightIsDecimal ? $rightValue->value : $this->numericToBcString($rightValue);

            if ($leftStr === null || $rightStr === null) {
                throw new EvaluationException('Arithmetic operators require numeric operands');
            }

            return $this->performBcArithmetic($leftStr, $rightStr, $operator);
        }

        if (!is_numeric($leftValue) || !is_numeric($rightValue)) {
            throw new EvaluationException('Arithmetic operators require numeric operands');
        }

        try {
            return Collection::single($operation($leftValue, $rightValue));
        } catch (\DivisionByZeroError) {
            return Collection::empty();
        }
    }

    /**
     * Perform bcmath arithmetic on two decimal strings, returning a FHIRPathDecimal result.
     */
    private function performBcArithmetic(string $left, string $right, TokenType $operator): Collection
    {
        // Both strings are guaranteed numeric at this point (from FHIRPathDecimal->value or numericToBcString)
        assert(is_numeric($left) && is_numeric($right));

        $leftPrec  = ($dotPos = strpos($left, '.'))  !== false ? strlen($left)  - $dotPos - 1 : 0;
        $rightPrec = ($dotPos = strpos($right, '.')) !== false ? strlen($right) - $dotPos - 1 : 0;
        $maxPrec   = max($leftPrec, $rightPrec);

        // Division and modulo by zero return empty per FHIRPath spec
        if (in_array($operator, [TokenType::DIVIDE, TokenType::DIV, TokenType::MOD], true)
            && bccomp($right, '0', $maxPrec + 2) === 0
        ) {
            return Collection::empty();
        }

        $result = match ($operator) {
            TokenType::PLUS     => bcadd($left, $right, $maxPrec),
            TokenType::MINUS    => bcsub($left, $right, $maxPrec),
            TokenType::MULTIPLY => bcmul($left, $right, $leftPrec + $rightPrec),
            TokenType::DIVIDE   => bcdiv($left, $right, max($maxPrec + self::DIVISION_GUARD_DIGITS, self::DIVISION_GUARD_DIGITS)),
            TokenType::DIV      => bcdiv($left, $right, 0),
            TokenType::MOD      => bcmod($left, $right, $maxPrec),
            default             => throw new EvaluationException("Operator {$operator->value} not supported for decimal arithmetic"),
        };

        // For DIV, return integer
        if ($operator === TokenType::DIV) {
            return Collection::single((int) $result);
        }

        // Trim trailing zeros after decimal point, preserving at least one decimal place
        if (str_contains($result, '.')) {
            $result = rtrim($result, '0');

            if (str_ends_with($result, '.')) {
                $result .= '0';
            }
        }

        return Collection::single(new FHIRPathDecimal($result));
    }

    /**
     * Convert a PHP numeric value to a bcmath-compatible string.
     *
     * For floats, sprintf('%.17g') is used instead of number_format() because:
     * - It captures all 17 significant digits of an IEEE 754 double, whereas
     *   a fixed decimal-place count (e.g. 14) under-represents small values
     *   (e.g. 1.23e-5 has only ~9 significant decimal places at 14dp).
     * - The %g format strips trailing zeros automatically.
     * - bcmath requires plain decimal notation, so any scientific-notation
     *   result (exponent outside [-4, 17)) is converted: the number of decimal
     *   places is derived from the exponent so that 17 significant digits are
     *   preserved in the fixed-point form.
     *
     * @return string|null Bcmath string, or null if the value is not numeric
     */
    private function numericToBcString(mixed $value): ?string
    {
        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            // 17 significant digits covers the full range of IEEE 754 double precision.
            $str = sprintf('%.17g', $value);

            // bcmath requires plain decimal notation; %g uses scientific notation when
            // the exponent is outside [-4, 17). Convert that to fixed-point when needed.
            if (stripos($str, 'e') !== false) {
                preg_match('/[eE]([+-]?\d+)/', $str, $m);
                $exp      = (int) ($m[1] ?? '0');
                // Negative exponent: need |exp| + 16 decimal places for 17 sig digits.
                // Positive exponent: integer part grows, fewer decimal places needed.
                $decimals = $exp < 0 ? abs($exp) + 16 : max(0, 16 - $exp);
                $str      = number_format($value, $decimals, '.', '');
            }

            return rtrim(rtrim($str, '0'), '.');
        }

        if (is_string($value) && is_numeric($value)) {
            return $value;
        }

        return null;
    }

    /**
     * Perform arithmetic on two quantities
     *
     * @param array{value: float, code: string, unit: string, system: string|null} $left
     * @param array{value: float, code: string, unit: string, system: string|null} $right
     * @param callable(float, float): float                                        $operation
     * @param TokenType                                                            $operator
     */
    private function performQuantityArithmetic(array $left, array $right, callable $operation, TokenType $operator): Collection
    {
        $leftCode  = $left['code'];
        $rightCode = $right['code'];

        // Handle multiplication and division specially
        if ($operator === TokenType::MULTIPLY) {
            // Convert both to base units
            $leftBase  = $this->tryConvertToBaseUnit($leftCode, $left['value']);
            $rightBase = $this->tryConvertToBaseUnit($rightCode, $right['value']);

            if ($leftBase !== null && $rightBase !== null) {
                // Multiply base values
                $resultValue = $leftBase['value'] * $rightBase['value'];

                // Combine units: m * m = m2, etc.
                if ($leftBase['base'] === $rightBase['base']) {
                    $resultCode = $leftBase['base'] . '2';
                } else {
                    $resultCode = $leftBase['base'] . '.' . $rightBase['base'];
                }

                return Collection::single([
                    'value'  => $resultValue,
                    'code'   => $resultCode,
                    'unit'   => $resultCode,
                    'system' => $left['system'] ?? 'http://unitsofmeasure.org',
                ]);
            }
        } elseif ($operator === TokenType::DIVIDE) {
            // Don't convert to base units - preserve original units in result
            $resultValue = $left['value'] / $right['value'];

            if ($leftCode === $rightCode) {
                $resultCode = '1';
            } else {
                $resultCode = $leftCode . '/' . $rightCode;
            }

            return Collection::single([
                'value'  => $resultValue,
                'code'   => $resultCode,
                'unit'   => $resultCode,
                'system' => $left['system'] ?? 'http://unitsofmeasure.org',
            ]);
        }

        // For addition/subtraction, just compute the result with the left unit
        // (ideally we'd convert right to left's unit first)
        $resultValue = $operation($left['value'], $right['value']);
        $resultCode  = $leftCode;

        return Collection::single([
            'value'  => $resultValue,
            'code'   => $resultCode,
            'unit'   => $resultCode,
            'system' => $left['system'] ?? 'http://unitsofmeasure.org',
        ]);
    }

    /**
     * Maps UCUM duration codes and calendar keywords to canonical duration keywords.
     *
     * Extra decimal places computed beyond the operands' own precision during division.
     *
     * Division can produce non-terminating decimals (e.g. 1/3 = 0.333...). Guard digits
     * are appended so that the stored result retains meaningful precision even after the
     * trailing-zero trim in performBcArithmetic(). Eight places cover the full range of
     * UCUM/FHIR decimal quantities (typically ≤ 4 dp) with comfortable headroom, without
     * generating excessively long strings for every division.
     *
     * The effective bcmath scale for a division is max($maxOperandPrec + DIVISION_GUARD_DIGITS, DIVISION_GUARD_DIGITS).
     */
    private const DIVISION_GUARD_DIGITS = 8;

    /**
     * UCUM codes 'a' and 'mo' are intentionally absent — they are not supported for
     * date arithmetic per the FHIRPath spec (tests marked invalid="execution").
     *
     * @var array<string, string>
     */
    private const DATE_UNIT_MAP = [
        // Calendar keywords (singular)
        'year'         => 'year',
        'years'        => 'year',
        'month'        => 'month',
        'months'       => 'month',
        'week'         => 'week',
        'weeks'        => 'week',
        'day'          => 'day',
        'days'         => 'day',
        'hour'         => 'hour',
        'hours'        => 'hour',
        'minute'       => 'minute',
        'minutes'      => 'minute',
        'second'       => 'second',
        'seconds'      => 'second',
        'millisecond'  => 'millisecond',
        'milliseconds' => 'millisecond',
        // UCUM codes (year 'a' and month 'mo' are intentionally absent)
        'wk'  => 'week',
        'd'   => 'day',
        'h'   => 'hour',
        'min' => 'minute',
        's'   => 'second',
        'ms'  => 'millisecond',
    ];

    /**
     * Perform date/datetime arithmetic: date +/- duration quantity.
     *
     * Per the FHIRPath spec, the quantity value is truncated to an integer before applying.
     * Output is formatted at the same precision as the input date string.
     *
     * @param array{value: float, code: string, unit: string, system: string|null} $quantity
     */
    private function performDateArithmetic(string $dateStr, array $quantity, TokenType $operator): Collection
    {
        $unitCode      = $quantity['code'];
        $canonicalUnit = self::DATE_UNIT_MAP[$unitCode] ?? null;

        if ($canonicalUnit === null) {
            // Unknown or unsupported unit (e.g. UCUM 'a', 'mo', or 'cm')
            throw new EvaluationException("Date arithmetic with unit '{$unitCode}' is not supported", 0, 0);
        }

        // Truncate to integer per FHIRPath spec (7.7 days = 7 days, 0.1 s = 0 s)
        $intValue = (int) $quantity['value'];

        if ($operator === TokenType::MINUS) {
            $intValue = -$intValue;
        }

        // Parse the date string (strip any legacy @ prefix)
        $dateStr = ltrim($dateStr, '@');

        // Determine input precision — using getDateTimePrecision which normalizes .000 → sec (6).
        // We separately track whether the input had explicit fractional seconds (even .000)
        // so the output format can preserve them.
        $inputPrecision = $this->comparisonService->getDateTimePrecision($dateStr);

        $fracDigits = 0;

        if (preg_match('/\.(\d+)/', $dateStr, $fracMatch)) {
            $fracDigits = strlen($fracMatch[1]);
            // Input has explicit fractional seconds: format output at millisecond level
            $inputPrecision = 7;
        }

        // Detect timezone suffix to preserve it in output
        $tzSuffix = '';
        $usesZ    = false;

        if (preg_match('/([+-]\d{2}:\d{2}|Z)$/', $dateStr, $tzMatch)) {
            $tzSuffix = $tzMatch[1];
            $usesZ    = $tzSuffix === 'Z';
        }

        // Build a parseable datetime string (pad partial dates to full datetime)
        $parseable = $this->makeDateParseable($dateStr);

        try {
            $dt = new \DateTimeImmutable($parseable);
        } catch (\Exception $e) {
            return Collection::empty();
        }

        // Apply the integer duration
        $dt = $this->applyDateDuration($dt, $canonicalUnit, $intValue);

        // Format output at the original input precision (with fractional seconds if present)
        $result = $this->formatDateAtPrecision($dt, $inputPrecision, $tzSuffix, $usesZ, $fracDigits);

        return Collection::single($result);
    }

    /**
     * Pad a partial date string to a full datetime so DateTimeImmutable can parse it.
     *
     * E.g. "1973-12-25" stays as-is (parseable), "1973-12" becomes "1973-12-01".
     */
    private function makeDateParseable(string $dateStr): string
    {
        // Already has time component — use as-is
        if (str_contains($dateStr, 'T')) {
            return $dateStr;
        }

        // YYYY-MM-DD — parseable
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        // YYYY-MM — pad day
        if (preg_match('/^\d{4}-\d{2}$/', $dateStr)) {
            return $dateStr . '-01';
        }

        // YYYY — pad month and day
        if (preg_match('/^\d{4}$/', $dateStr)) {
            return $dateStr . '-01-01';
        }

        return $dateStr;
    }

    /**
     * Apply an integer calendar duration to a DateTimeImmutable.
     *
     * Uses DateInterval for calendar-correct arithmetic (e.g. adding 1 month to Jan 31 = Feb 28).
     */
    private function applyDateDuration(\DateTimeImmutable $dt, string $unit, int $value): \DateTimeImmutable
    {
        $absValue = abs($value);
        $invert   = $value < 0 ? 1 : 0;

        $interval = match ($unit) {
            'year'        => new \DateInterval("P{$absValue}Y"),
            'month'       => new \DateInterval("P{$absValue}M"),
            'week'        => new \DateInterval('P' . ($absValue * 7) . 'D'),
            'day'         => new \DateInterval("P{$absValue}D"),
            'hour'        => new \DateInterval("PT{$absValue}H"),
            'minute'      => new \DateInterval("PT{$absValue}M"),
            'second'      => new \DateInterval("PT{$absValue}S"),
            'millisecond' => null,
            default       => null,
        };

        if ($unit === 'millisecond') {
            // PHP DateTimeImmutable doesn't have millisecond intervals, use microseconds
            $microseconds = $value * 1000;
            $sign         = $microseconds >= 0 ? '+' : '-';
            $abs          = abs($microseconds);

            return $dt->modify("{$sign}{$abs} microseconds");
        }

        if ($interval === null) {
            return $dt;
        }

        $interval->invert = $invert;

        return $dt->add($interval);
    }

    /**
     * Format a DateTimeImmutable back to an ISO string at the original input precision.
     *
     * @param int|null $precision  Precision level 1-7 (see ComparisonService::getDateTimePrecision)
     * @param string   $tzSuffix   Original timezone suffix (e.g. "+10:00", "Z", "")
     * @param bool     $usesZ      Whether the original used "Z" instead of "+00:00"
     * @param int      $fracDigits Number of fractional-second digits in the original (0 = none)
     */
    private function formatDateAtPrecision(\DateTimeImmutable $dt, ?int $precision, string $tzSuffix, bool $usesZ, int $fracDigits = 0): string
    {
        $hasTz = $tzSuffix !== '';
        $tz    = $hasTz ? $this->formatTzSuffix($dt, $usesZ) : '';

        if ($precision === 7) {
            // Format fractional seconds from microseconds, padded to $fracDigits (min 3)
            $digits   = max($fracDigits, 3);
            $us       = (int) $dt->format('u'); // microseconds 0-999999
            $ms       = (int) round($us / 1000); // milliseconds 0-999
            $fracPart = str_pad((string) $ms, $digits, '0', STR_PAD_LEFT);

            return $dt->format('Y-m-d\TH:i:s') . '.' . $fracPart . $tz;
        }

        return match ($precision) {
            1       => $dt->format('Y'),
            2       => $dt->format('Y-m'),
            3       => $dt->format('Y-m-d'),
            4       => $dt->format('Y-m-d\TH') . $tz,
            5       => $dt->format('Y-m-d\TH:i') . $tz,
            6       => $dt->format('Y-m-d\TH:i:s') . $tz,
            default => $dt->format('Y-m-d'),
        };
    }

    /**
     * Format the timezone suffix of a DateTimeImmutable, preserving Z vs +00:00.
     */
    private function formatTzSuffix(\DateTimeImmutable $dt, bool $usesZ): string
    {
        $offset = $dt->format('P'); // "+10:00" or "+00:00"

        if ($usesZ && $offset === '+00:00') {
            return 'Z';
        }

        return $offset;
    }

    /**
     * Try to convert a quantity to its base unit
     *
     * @return array{base: string, value: float}|null
     */
    private function tryConvertToBaseUnit(string $unit, float $value): ?array
    {
        // Use the same conversion logic from ComparisonService
        $conversions = [
            'kg' => ['base' => 'kg', 'factor' => 1.0],
            'g'  => ['base' => 'kg', 'factor' => 0.001],
            'mg' => ['base' => 'kg', 'factor' => 0.000001],
            'm'  => ['base' => 'm', 'factor' => 1.0],
            'cm' => ['base' => 'm', 'factor' => 0.01],
            'mm' => ['base' => 'm', 'factor' => 0.001],
            'km' => ['base' => 'm', 'factor' => 1000.0],
        ];

        $definition = $conversions[$unit] ?? null;
        if ($definition === null) {
            return null;
        }

        return [
            'base'  => $definition['base'],
            'value' => $value * $definition['factor'],
        ];
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

        // Normalize values to handle FHIR primitives and enums
        $leftValue  = $this->normalizeValue($left->first());
        $rightValue = $this->normalizeValue($right->first());

        $result = $operation($leftValue, $rightValue);

        return Collection::single($result);
    }

    /**
     * Evaluate string concatenation
     *
     * Per FHIRPath spec §6.7: unlike +, the & operator treats empty collections as
     * empty strings rather than propagating empty. So 'a' & {} = 'a', not {}.
     */
    private function evaluateStringConcat(Collection $left, Collection $right): Collection
    {
        // Non-empty multi-item collections are still an error
        if (!$left->isEmpty() && !$left->isSingle()) {
            throw new EvaluationException('String concatenation (&) requires single-item operands');
        }

        if (!$right->isEmpty() && !$right->isSingle()) {
            throw new EvaluationException('String concatenation (&) requires single-item operands');
        }

        // Empty collection treated as empty string (per spec)
        $leftValue  = $left->isEmpty() ? '' : (string) $this->normalizeValue($left->first());
        $rightValue = $right->isEmpty() ? '' : (string) $this->normalizeValue($right->first());

        return Collection::single($leftValue . $rightValue);
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

        // Three-valued logic for implies per FHIRPath spec:
        // false implies * = true
        if ($leftBool === false) {
            return Collection::single(true);
        }

        // empty implies true = true
        if ($leftBool === null && $rightBool === true) {
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

        // Otherwise empty (unknown): true implies empty, empty implies false, empty implies empty
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

        // Normalize FHIR primitive wrappers (e.g. BooleanPrimitive → bool) before
        // applying singleton evaluation so that Patient.active (a BooleanPrimitive)
        // evaluates to the correct false/true instead of always returning true.
        $value = $this->normalizeValue($collection->first());

        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        // Per FHIRPath spec §6.1 singleton evaluation: any non-null single value
        // in a boolean context evaluates to true.
        return true;
    }
}
