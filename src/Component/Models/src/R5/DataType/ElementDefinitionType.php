<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The data type or resource that the value of this element is permitted to be.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.type', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'eld-4',
    severity: 'error',
    expression: 'aggregation.empty() or (code = \'Reference\') or (code = \'canonical\') or (code = \'CodeableReference\')',
    human: 'Aggregation may only be specified if one of the allowed types for the element is a reference',
)]
#[FHIRPathInvariant(
    key: 'eld-17',
    severity: 'error',
    expression: '(code=\'Reference\' or code = \'canonical\' or code = \'CodeableReference\') or targetProfile.empty()',
    human: 'targetProfile is only allowed if the type is Reference or canonical',
)]
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
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/elementdefinition-types', strength: 'extensible')]
        public ?UriPrimitive $code = null,
        /** @var array<CanonicalPrimitive> profile Profiles (StructureDefinition or IG) - one must apply */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/StructureDefinition',
            'http://hl7.org/fhir/StructureDefinition/ImplementationGuide',
        ])]
        public array $profile = [],
        /** @var array<CanonicalPrimitive> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/StructureDefinition',
            'http://hl7.org/fhir/StructureDefinition/ImplementationGuide',
        ])]
        public array $targetProfile = [],
        /** @var array<AggregationModeType> aggregation contained | referenced | bundled - how aggregated */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/resource-aggregation-mode|5.0.0', strength: 'required')]
        public array $aggregation = [],
        /** @var ReferenceVersionRulesType|null versioning either | independent | specific */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/reference-version-rules|5.0.0', strength: 'required')]
        public ?ReferenceVersionRulesType $versioning = null,
    ) {
        parent::__construct($id, $extension);
    }
}
