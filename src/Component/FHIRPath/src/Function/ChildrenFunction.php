<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * children(): collection
 *
 * Returns a collection containing all direct child nodes of each item in the
 * focus collection. For objects and associative arrays, children are all
 * property values (list-valued properties are flattened into individual items).
 * For scalars and null, the result is empty.
 *
 * Equivalent to evaluating every named property of the node and merging the
 * results, consistent with the property-navigation semantics of the evaluator.
 *
 * Spec reference: FHIRPath §5.8
 *
 * @author FHIR Tools Contributors
 */
final class ChildrenFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('children');
    }

    /**
     * Execute the children function to get direct child nodes.
     *
     * Returns all immediate child values (one level deep) from each item in the input.
     *
     * @param Collection        $input      The input collection to get children from
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection All direct child values from all input items
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for children)
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $children = [];
        foreach ($input as $item) {
            // Get all direct child values from this item
            foreach ($this->getNodeChildren($item) as $child) {
                $children[] = $child;
            }
        }

        return Collection::from($children);
    }
}
