<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Informant',
    name: 'Informant',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'informant-entity',
    severity: 'error',
    expression: '(assignedEntity | relatedEntity).count() = 1',
    human: 'AssignedEntity and RelatedEntity are mutually exclusive (one must be present)',
)]
class Informant extends InfrastructureRoot
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@typeCode')]
        public string $typeCode = 'INF',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@contextControlCode')]
        public string $contextControlCode = 'OP',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/AssignedEntity',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?AssignedEntity $assignedEntity = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/RelatedEntity',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?RelatedEntity $relatedEntity = null,
    ) {
    }
}
