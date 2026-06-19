<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Reference',
    name: 'Reference',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'reference-external',
    severity: 'error',
    expression: '(externalAct | externalObservation | externalProcedure | externalDocument).count() = 1',
    human: 'Must contain one (and only one) external reference',
)]
class Reference extends InfrastructureRoot
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@typeCode')]
        public ?string $typeCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?BL $seperatableInd = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ExternalAct',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ExternalAct $externalAct = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ExternalObservation',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ExternalObservation $externalObservation = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ExternalProcedure',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ExternalProcedure $externalProcedure = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ExternalDocument',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ExternalDocument $externalDocument = null,
    ) {
    }
}
