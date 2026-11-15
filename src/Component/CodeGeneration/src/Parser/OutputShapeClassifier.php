<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

/**
 * Decides how an operation's response is shaped on the wire, from its OperationDefinition alone.
 *
 * "The wire format of an operation response IS a `Parameters` resource" is true for only about a
 * quarter of operations. Measured across the shipped core packages (`inventory.md`):
 *
 * | Shape             | R4       | R4B      | R5       |
 * |-------------------|----------|----------|----------|
 * | Parameters        | 14 (30%) | 14 (30%) | 16 (26%) |
 * | BareResource      | 27 (57%) | 27 (57%) | 39 (64%) |
 * | NamedBareResource | 3        | 3        | 3        |
 * | NoOutput          | 3        | 3        | 2        |
 *
 * ## The rule is keyed on the parameter *name*, not on cardinality
 *
 * The R4 specification (`hl7.org/fhir/R4/operations.html`, and identically in R5) says:
 *
 * > "If there is only one *out* parameter, which is a Resource with the parameter name "return"
 * > then the parameter format is not used, and the response is simply the resource itself."
 *
 * Both conditions bind. A sole resource-typed OUT parameter named `return` is returned **bare** —
 * literally un-wrapped, not wrapped-by-convention. A sole resource-typed OUT parameter under any
 * *other* name fails the name condition, so the parameter format **is** used and the resource
 * arrives inside a one-parameter `Parameters`.
 *
 * `ValueSet/$expand` returns a `ValueSet` named `return` — bare. `Resource/$graph` returns a
 * `Bundle` named `result` — wrapped. Same cardinality, same "sole resource output" description,
 * opposite wire shapes. Classifying on cardinality alone gets the second one wrong, which is why
 * this class exists rather than an inline conditional.
 *
 * Returns type codes and names verbatim; mapping a resource type code to a generated class is a
 * generator concern requiring `BuilderContext`.
 *
 * @author Ardenexal
 */
final class OutputShapeClassifier
{
    /**
     * The name the specification's un-wrap rule is conditioned on. Not configurable: it is a literal
     * in the normative text, not a convention.
     */
    public const string BARE_RESOURCE_PARAMETER_NAME = 'return';

    public const string SHAPE_PARAMETERS = 'parameters';

    public const string SHAPE_BARE_RESOURCE = 'bare-resource';

    public const string SHAPE_NAMED_BARE_RESOURCE = 'named-bare-resource';

    public const string SHAPE_NO_OUTPUT = 'no-output';

    /**
     * Classify one OperationDefinition.
     *
     * @param array<string, mixed> $definition A full `OperationDefinition` resource
     * @param TypeIndexInterface   $types      Resolves whether an output type names a resource
     *
     * @return array{shape: string, outputType: string|null, outputParameterName: string|null}
     *                                                                                         `outputType` is the FHIR resource type
     *                                                                                         for the two bare shapes and null
     *                                                                                         otherwise; `outputParameterName` is set
     *                                                                                         only for NamedBareResource, where the
     *                                                                                         name cannot be assumed to be `return`
     */
    public function classify(array $definition, TypeIndexInterface $types): array
    {
        $outputs = $this->outputParameters($definition);

        if ($outputs === []) {
            return ['shape' => self::SHAPE_NO_OUTPUT, 'outputType' => null, 'outputParameterName' => null];
        }

        // More than one OUT parameter can only be carried by a real Parameters resource.
        if (count($outputs) > 1) {
            return ['shape' => self::SHAPE_PARAMETERS, 'outputType' => null, 'outputParameterName' => null];
        }

        $sole = $outputs[0];
        $type = is_string($sole['type'] ?? null) ? $sole['type'] : null;

        // A sole *primitive* or complex-typed output still needs the envelope — there is nothing to
        // un-wrap to. Only a resource can be a response body on its own.
        if ($type === null || !$this->isResourceType($type, $types)) {
            return ['shape' => self::SHAPE_PARAMETERS, 'outputType' => null, 'outputParameterName' => null];
        }

        $name = is_string($sole['name'] ?? null) ? $sole['name'] : '';

        if ($name === self::BARE_RESOURCE_PARAMETER_NAME) {
            return [
                'shape'               => self::SHAPE_BARE_RESOURCE,
                'outputType'          => $type,
                // `return` by definition — carrying it would invite callers to treat it as variable.
                'outputParameterName' => null,
            ];
        }

        return [
            'shape'               => self::SHAPE_NAMED_BARE_RESOURCE,
            'outputType'          => $type,
            'outputParameterName' => $name,
        ];
    }

    /**
     * Is this FHIR type code a resource rather than a data type?
     *
     * Answered from the type's own StructureDefinition (`kind === 'resource'`), not from a
     * capitalisation heuristic. **The heuristic was tried and is wrong**: `Meta` is capitalised but
     * is a `complex-type`, and R4 has three operations (`$meta`, `$meta-add`, `$meta-delete`) whose
     * sole OUT parameter is `return:Meta`. Treating those as bare-resource responses classified
     * exactly 3 operations wrongly in every version — caught by the pre-registered `inventory.md`
     * counts, which is what they exist for.
     *
     * `Meta` cannot be a response body on its own; those operations answer with a `Parameters`.
     */
    private function isResourceType(string $type, TypeIndexInterface $types): bool
    {
        if ($type === '') {
            return false;
        }

        // `Any` is a wildcard used in OperationDefinition parameter types ("any resource type"),
        // not a real StructureDefinition — R4's two `$apply` operations declare `return:Any`. It
        // means a resource, so it takes the bare shape.
        if ($type === 'Any') {
            return true;
        }

        return $types->kindOf($type) === 'resource';
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return list<array<string, mixed>>
     */
    private function outputParameters(array $definition): array
    {
        $parameters = $definition['parameter'] ?? [];

        if (!is_array($parameters)) {
            return [];
        }

        return array_values(array_filter(
            $parameters,
            static fn (mixed $p): bool => is_array($p) && ($p['use'] ?? null) === 'out',
        ));
    }
}
