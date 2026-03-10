<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathTemporalTypeInterface;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRTypedScalar;
use Ardenexal\FHIRTools\Component\FHIRPath\Type\TypeInfo;

/**
 * FHIRPath type() function.
 *
 * Returns type information for each item in the input collection. Per FHIRPath
 * specification, type() returns a ClassInfo structure with namespace and name
 * properties identifying the runtime type of each value.
 *
 * - For FHIRPath literal scalars (PHP bool/int/float/string): namespace='System'
 * - For FHIRPath value objects (FHIRPathDecimal, dates): namespace='System'
 * - For FHIR primitive wrappers (BooleanPrimitive, etc.): namespace='FHIR'
 * - For FHIR resources and complex types: namespace='FHIR'
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

        $result = [];
        foreach ($input as $item) {
            $result[] = $this->getTypeInfo($item);
        }

        return Collection::from($result);
    }

    /**
     * Get type information for a value.
     */
    private function getTypeInfo(mixed $value): TypeInfo
    {
        if ($value === null) {
            return TypeInfo::system('Null');
        }

        // FHIR-typed scalar: PHP scalar from a FHIR resource property, preserving FHIR type context.
        // This is FHIR namespace (e.g. Patient.active is FHIR.boolean, not System.Boolean).
        if ($value instanceof FHIRTypedScalar) {
            return TypeInfo::fhir($value->fhirType);
        }

        // FHIRPath literal scalars produced by the parser/evaluator → System namespace
        if (is_bool($value)) {
            return TypeInfo::system('Boolean');
        }

        if (is_int($value)) {
            return TypeInfo::system('Integer');
        }

        // FHIRPath decimal value objects → System.Decimal
        if ($value instanceof FHIRPathDecimal) {
            return TypeInfo::system('Decimal');
        }

        if (is_float($value)) {
            return TypeInfo::system('Decimal');
        }

        // FHIRPath temporal value objects → System namespace with their specific type
        if ($value instanceof FHIRPathTemporalTypeInterface) {
            $typeName = ucfirst($value->getTemporalTypeName()); // 'date' → 'Date', etc.

            return TypeInfo::system($typeName);
        }

        if (is_string($value)) {
            return TypeInfo::system('String');
        }

        // FHIR resource/complex type (associative array)
        if (is_array($value)) {
            if (isset($value['resourceType']) && is_string($value['resourceType'])) {
                return TypeInfo::fhir($value['resourceType']);
            }

            return TypeInfo::system('Collection');
        }

        // FHIR object types (generated models)
        if (is_object($value)) {
            $ref = new \ReflectionClass($value);

            // Walk hierarchy for #[FHIRPrimitive] — subclasses (e.g. NameUseType → CodePrimitive)
            // carry the attribute on an ancestor rather than directly.
            $primitive = $this->findPrimitiveAttribute($ref);
            if ($primitive !== null) {
                return TypeInfo::fhir($primitive->primitiveType);
            }

            // Walk hierarchy for #[FhirResource(type: '...')] — returns 'Patient' not 'PatientResource'
            $resourceType = $this->findResourceTypeFromAttribute($ref);
            if ($resourceType !== null) {
                return TypeInfo::fhir($resourceType);
            }

            // Fallback: check property/method-based resourceType (array-decoded resources)
            if (property_exists($value, 'resourceType') && is_string($value->resourceType)) {
                return TypeInfo::fhir($value->resourceType);
            }

            if (method_exists($value, 'getResourceType')) {
                /** @var callable(): string $getter */
                $getter = [$value, 'getResourceType'];

                return TypeInfo::fhir($getter());
            }

            // Generated FHIR model without attribute — use class short name
            $class = get_class($value);
            if (str_contains($class, '\\FHIR\\') || str_contains($class, '\\Models\\')) {
                $parts = explode('\\', $class);

                return TypeInfo::fhir(end($parts));
            }

            return TypeInfo::system('Object');
        }

        return TypeInfo::system('Any');
    }

    /**
     * Walk the class hierarchy of $ref looking for a #[FHIRPrimitive] attribute.
     *
     * Returns the first FHIRPrimitive instance found, or null when the class tree
     * carries no such attribute.
     *
     * @param \ReflectionClass<object> $ref
     */
    private function findPrimitiveAttribute(\ReflectionClass $ref): ?FHIRPrimitive
    {
        do {
            $attrs = $ref->getAttributes(FHIRPrimitive::class);
            if (!empty($attrs)) {
                /** @var FHIRPrimitive */
                return $attrs[0]->newInstance();
            }

            $ref = $ref->getParentClass();
        } while ($ref !== false);

        return null;
    }

    /**
     * Walk the class hierarchy of $ref looking for a #[FhirResource(type: '...')] attribute.
     *
     * Returns the canonical FHIR resource type string (e.g. 'Patient') when found,
     * or null when the class tree carries no such attribute.
     *
     * @param \ReflectionClass<object> $ref
     */
    private function findResourceTypeFromAttribute(\ReflectionClass $ref): ?string
    {
        do {
            foreach ($ref->getAttributes() as $attr) {
                if (str_ends_with($attr->getName(), 'FhirResource')) {
                    $args = $attr->getArguments();
                    $type = $args['type'] ?? $args[0] ?? null;

                    return is_string($type) ? $type : null;
                }
            }

            $ref = $ref->getParentClass();
        } while ($ref !== false);

        return null;
    }
}
