<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * conformsTo(structure: String): Boolean
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Returns true if the single input item conforms to the StructureDefinition
 * identified by the given canonical URL; false otherwise.
 *
 * Because profile validation has no universal HTTP pattern — it may be handled
 * by HAPI FHIR's $validate operation, Firely SDK, HL7 validator CLI, or a custom
 * engine — this function delegates to a user-supplied callable rather than making
 * HTTP calls directly.
 *
 * Configure the validator on FHIRPathEvaluator before evaluating expressions that
 * use conformsTo():
 *
 *   $evaluator->setConformsToValidator(function (mixed $resource, string $url): bool {
 *       return $myProfileEngine->validate($resource, $url);
 *   });
 *
 * Returns:
 *  - `[true]`  — resource conforms to the given profile
 *  - `[false]` — resource does not conform
 *  - `[]`      — input is not exactly one item, or structure parameter is not a non-empty string
 *
 * Throws EvaluationException when no validator callable is configured.
 *
 * Does NOT depend on Models or CodeGeneration.
 *
 * Spec reference: FHIR R4 FHIRPath supplement §2.4
 *
 * @author FHIR Tools Contributors
 */
final class ConformsToFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('conformsTo');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        // Spec: requires exactly one item in the collection
        if ($input->count() !== 1) {
            return Collection::empty();
        }

        $evaluator  = $context->getEvaluator();
        $validator  = $evaluator?->getConformsToValidator();

        if ($validator === null) {
            throw new EvaluationException('conformsTo() requires a profile validator. Configure one with FHIRPathEvaluator::setConformsToValidator().', 0, 0);
        }

        // Evaluate the structure parameter in the current context
        $paramResult = $evaluator->evaluateWithContext($parameters[0], $context);
        $structure   = $paramResult->first();

        if (!is_string($structure) || $structure === '') {
            return Collection::empty();
        }

        $item   = $input->first();
        $result = ($validator)($item, $structure);

        return Collection::single($result);
    }
}
