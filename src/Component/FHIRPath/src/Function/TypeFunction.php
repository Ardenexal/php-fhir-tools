<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\TypeInfo;

/**
 * FHIRPath type() function.
 *
 * Returns type information for the input item. Per FHIRPath specification,
 * type() returns a ClassInfo structure with namespace and name properties.
 *
 * - For primitive PHP types: namespace='System', name='Integer'|'String'|'Boolean'|'Decimal'
 * - For FHIR primitives: namespace='FHIR', name='boolean'|'string'|'integer', etc.
 * - For FHIR resources/complex types: namespace='FHIR', name=resource type (e.g., 'Patient')
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class TypeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('type');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        if ($input->count() > 1) {
            // type() only operates on single items
            return Collection::empty();
        }

        $value    = $input->first();
        $typeInfo = $this->getTypeInfo($value);

        return Collection::single($typeInfo);
    }

    /**
     * Get type information for a value.
     *
     * @param mixed $value The value to get type info for
     *
     * @return TypeInfo
     */
    private function getTypeInfo(mixed $value): TypeInfo
    {
        // Handle null
        if ($value === null) {
            return TypeInfo::system('Null');
        }

        // PHP primitive types → System namespace
        if (is_bool($value)) {
            return TypeInfo::system('Boolean');
        }

        if (is_int($value)) {
            return TypeInfo::system('Integer');
        }

        if (is_float($value)) {
            return TypeInfo::system('Decimal');
        }

        if (is_string($value)) {
            return TypeInfo::system('String');
        }

        // FHIR primitives (arrays with @value wrapper) and FHIR resources/types
        if (is_array($value)) {
            // FHIR primitive type - has @value wrapper
            if (isset($value['@value'])) {
                // Get the FHIR primitive type from context if available
                // For now, infer from the PHP type of the value
                $primitiveValue = $value['@value'];
                if (is_bool($primitiveValue)) {
                    return TypeInfo::fhir('boolean');
                }
                if (is_int($primitiveValue)) {
                    return TypeInfo::fhir('integer');
                }
                if (is_float($primitiveValue)) {
                    return TypeInfo::fhir('decimal');
                }
                if (is_string($primitiveValue)) {
                    // Default to 'string' but could be other FHIR string types
                    // (code, uri, etc.) - would need additional metadata to distinguish
                    return TypeInfo::fhir('string');
                }
            }

            // FHIR resource/complex type - check for resourceType property
            if (isset($value['resourceType']) && is_string($value['resourceType'])) {
                return TypeInfo::fhir($value['resourceType']);
            }

            // Generic array/collection
            return TypeInfo::system('Collection');
        }

        // FHIR object types (generated models)
        if (is_object($value)) {
            // Check for FHIRPrimitive attribute
            $ref   = new \ReflectionClass($value);
            $attrs = $ref->getAttributes(FHIRPrimitive::class);

            if (!empty($attrs)) {
                /** @var FHIRPrimitive $primitive */
                $primitive = $attrs[0]->newInstance();

                return TypeInfo::fhir($primitive->primitiveType);
            }

            // Check if object has resourceType property
            if (property_exists($value, 'resourceType') && is_string($value->resourceType)) {
                return TypeInfo::fhir($value->resourceType);
            }

            // Check if it's a FHIR resource method
            if (method_exists($value, 'getResourceType')) {
                /** @var callable(): string $getter */
                $getter = [$value, 'getResourceType'];

                return TypeInfo::fhir($getter());
            }

            // Get type from class name
            $class = get_class($value);

            // Check if it's a generated FHIR model
            if (str_contains($class, '\\FHIR\\') || str_contains($class, '\\Models\\')) {
                $parts    = explode('\\', $class);
                $typeName = end($parts);

                return TypeInfo::fhir($typeName);
            }

            // Generic object
            return TypeInfo::system('Object');
        }

        // Fallback
        return TypeInfo::system('Any');
    }
}
