<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * descendants(): collection
 *
 * Returns all descendant nodes (children, grandchildren, etc.) of each item
 * in the focus collection. The input items themselves are NOT included.
 *
 * Cycle-safe: PHP arrays are value types so cannot form true cycles; PHP
 * objects are tracked by identity (spl_object_hash) to prevent infinite loops
 * on circular object graphs.
 *
 * Equivalent to: children() | children().children() | ... until stable
 *
 * Spec reference: FHIRPath §5.8
 *
 * @author FHIR Tools Contributors
 */
final class DescendantsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('descendants');
    }

    /**
     * Execute the descendants function to get all nested child nodes.
     *
     * Returns all descendant values (at all nesting levels) using breadth-first traversal.
     * Tracks visited objects to prevent infinite loops on circular references.
     *
     * @param Collection        $input      The input collection to get descendants from
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection All descendant values from all input items
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for descendants)
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $result     = [];
        $seenHashes = []; // Tracks objects we've already visited to prevent infinite loops

        foreach ($input as $root) {
            // Breadth-first traversal of children, grandchildren, etc.
            // The root itself is not added — only its descendants.
            $queue = $this->getNodeChildren($root);

            while (!empty($queue)) {
                $next = []; // Items to process in the next iteration

                foreach ($queue as $node) {
                    // Cycle guard for PHP objects (only type that can form true cycles)
                    if (is_object($node)) {
                        $hash = spl_object_hash($node); // Unique ID for this object instance
                        if (isset($seenHashes[$hash])) {
                            // Already visited this object, skip to avoid infinite loop
                            continue;
                        }

                        $seenHashes[$hash] = true; // Mark as visited
                    }

                    $result[] = $node;

                    // Queue this node's own children for the next pass
                    // This creates a breadth-first traversal (all children, then all grandchildren, etc.)
                    foreach ($this->getNodeChildren($node) as $child) {
                        $next[] = $child;
                    }
                }

                $queue = $next;
            }
        }

        return Collection::from($result);
    }
}
