<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The data type or resource that the value of this element is permitted to be.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.type', fhirVersion: 'R4')]
class ElementDefinitionType extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var UriPrimitive|null code Data type or Resource (reference to definition) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $code = null,
        /** @var array<CanonicalPrimitive> profile Profiles (StructureDefinition or IG) - one must apply */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $profile = [],
        /** @var array<CanonicalPrimitive> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $targetProfile = [],
        /** @var array<AggregationModeType> aggregation contained | referenced | bundled - how aggregated */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $aggregation = [],
        /** @var ReferenceVersionRulesType|null versioning either | independent | specific */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ReferenceVersionRulesType $versioning = null,
    ) {
        parent::__construct($id, $extension);
    }
}
