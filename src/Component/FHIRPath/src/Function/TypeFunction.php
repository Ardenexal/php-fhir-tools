<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProviderInterface;
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
    private FHIRStructureKindProviderInterface $structureKinds;

    private FHIRMetadataExtractorInterface $metadata;

    /**
     * Both collaborators are optional so the registry can keep building this function with no
     * arguments. Passing the shared instances lets one set of caches serve every function.
     */
    public function __construct(
        ?FHIRStructureKindProviderInterface $structureKinds = null,
        ?FHIRMetadataExtractorInterface $metadata = null,
    ) {
        $this->structureKinds = $structureKinds ?? new FHIRStructureKindProvider();
        $this->metadata       = $metadata       ?? new FHIRMetadataExtractor();

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
            // Walk hierarchy for #[FHIRPrimitive] — subclasses (e.g. NameUseType → CodePrimitive)
            // carry the attribute on an ancestor rather than directly.
            $primitive = $this->structureKinds->nearestPrimitiveAttribute($value);
            if ($primitive !== null) {
                return TypeInfo::fhir($primitive->primitiveType);
            }

            // Walk hierarchy for #[FhirResource(type: '...')] — returns 'Patient' not 'PatientResource'
            $resourceType = $this->metadata->extractResourceType($value);
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
}
