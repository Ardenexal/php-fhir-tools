<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\IdentifierNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\MemberAccessNode;

/**
 * FHIRPath ofType() function.
 *
 * Filters the collection to only items whose runtime type matches the specified
 * type specifier. The type specifier may be:
 *  - A bare name:             ofType(Patient), ofType(integer)
 *  - A System.* name:         ofType(System.Boolean), ofType(System.Integer)
 *  - A FHIR.* name:           ofType(FHIR.Patient), ofType(FHIR.string)
 *
 * The type specifier argument is treated as a literal qualified identifier, not
 * as an evaluated expression, so ofType(FHIR.Patient) works even though
 * "FHIR.Patient" is not a valid FHIRPath navigation path.
 *
 * Per FHIRPath spec: ofType(type) ≡ where($this is type)
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class OfTypeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('ofType');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        // Extract the type name directly from the AST node to support qualified names
        // like System.Boolean and FHIR.Patient (which are not valid expression paths).
        $rawTypeName = $this->extractTypeSpecifier($parameters[0]);

        if ($rawTypeName === null) {
            // Fall back: evaluate as expression and expect a string result
            $evaluator = $context->getEvaluator();
            if ($evaluator === null) {
                throw new EvaluationException('ofType() requires a type name', 0, 0);
            }

            $typeResult = $evaluator->evaluateWithContext($parameters[0], $context);
            if ($typeResult->isEmpty() || !is_string($typeResult->first())) {
                throw EvaluationException::invalidFunctionParameter('ofType', 'typeName', 'string');
            }

            $rawTypeName = $typeResult->first();
        }

        // Resolve namespace qualifiers: System.Boolean → boolean, FHIR.Patient → Patient
        $evaluator = $context->getEvaluator();
        $typeName  = $evaluator !== null
            ? $evaluator->getTypeResolver()->normalizeTypeName($rawTypeName)
            : $rawTypeName;

        // Filter input items by type using the type resolver
        $typeResolver = $evaluator?->getTypeResolver();

        $items = [];
        foreach ($input as $item) {
            if ($typeResolver !== null) {
                if ($typeResolver->isOfType($item, $typeName)) {
                    $items[] = $item;
                }
            } else {
                // Fallback: object class name matching only
                if (is_object($item)) {
                    $className = get_class($item);
                    $shortName = substr($className, strrpos($className, '\\') + 1);
                    if ($shortName === $typeName || $className === $typeName) {
                        $items[] = $item;
                    }
                }
            }
        }

        return Collection::from($items);
    }

    /**
     * Extract a type specifier string from an AST expression node without evaluation.
     *
     * Handles:
     *  - IdentifierNode('Patient')                             → 'Patient'
     *  - MemberAccessNode(IdentifierNode('FHIR'), IdentifierNode('Patient')) → 'FHIR.Patient'
     *  - MemberAccessNode(IdentifierNode('System'), IdentifierNode('Boolean')) → 'System.Boolean'
     *
     * Returns null for any other node type (caller falls back to expression evaluation).
     */
    private function extractTypeSpecifier(ExpressionNode $node): ?string
    {
        if ($node instanceof IdentifierNode) {
            return $node->getName();
        }

        if ($node instanceof MemberAccessNode) {
            $object = $node->getObject();
            $member = $node->getMember();

            if ($object instanceof IdentifierNode && $member instanceof IdentifierNode) {
                return $object->getName() . '.' . $member->getName();
            }
        }

        return null;
    }
}
