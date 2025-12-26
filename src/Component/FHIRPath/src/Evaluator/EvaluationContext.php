<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * Context for FHIRPath expression evaluation
 *
 * Maintains state during evaluation including the root resource,
 * current evaluation node, variables, and external constants.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class EvaluationContext
{
    /**
     * @param array<string, mixed> $variables Variable storage ($this, $index, $total)
     * @param array<string, mixed> $externalConstants External constants (%)
     */
    public function __construct(
        private mixed $rootResource = null,
        private mixed $currentNode = null,
        private array $variables = [],
        private array $externalConstants = []
    ) {
    }

    /**
     * Get the root resource being evaluated
     */
    public function getRootResource(): mixed
    {
        return $this->rootResource;
    }

    /**
     * Set the root resource
     */
    public function setRootResource(mixed $resource): void
    {
        $this->rootResource = $resource;
    }

    /**
     * Get the current evaluation node
     */
    public function getCurrentNode(): mixed
    {
        return $this->currentNode;
    }

    /**
     * Create a new context with a different current node
     */
    public function withCurrentNode(mixed $node): self
    {
        $context = clone $this;
        $context->currentNode = $node;
        return $context;
    }

    /**
     * Get a variable value
     */
    public function getVariable(string $name): mixed
    {
        return $this->variables[$name] ?? null;
    }

    /**
     * Set a variable value
     */
    public function setVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * Check if a variable exists
     */
    public function hasVariable(string $name): bool
    {
        return array_key_exists($name, $this->variables);
    }

    /**
     * Create a new context with an additional variable
     */
    public function withVariable(string $name, mixed $value): self
    {
        $context = clone $this;
        $context->variables[$name] = $value;
        return $context;
    }

    /**
     * Get an external constant value
     */
    public function getExternalConstant(string $name): mixed
    {
        return $this->externalConstants[$name] ?? null;
    }

    /**
     * Set an external constant value
     */
    public function setExternalConstant(string $name, mixed $value): void
    {
        $this->externalConstants[$name] = $value;
    }

    /**
     * Check if an external constant exists
     */
    public function hasExternalConstant(string $name): bool
    {
        return array_key_exists($name, $this->externalConstants);
    }

    /**
     * Create a new context with an additional external constant
     */
    public function withExternalConstant(string $name, mixed $value): self
    {
        $context = clone $this;
        $context->externalConstants[$name] = $value;
        return $context;
    }

    /**
     * Create a context for evaluating a collection item with iteration variables
     */
    public function withIterationVariables(mixed $item, int $index, int $total): self
    {
        $context = clone $this;
        $context->currentNode = $item;
        $context->variables['this'] = $item;
        $context->variables['index'] = $index;
        $context->variables['total'] = $total;
        return $context;
    }
}
