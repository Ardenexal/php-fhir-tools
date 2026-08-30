<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/AssignedEntity-sdtcPatient',
    name: 'AssignedEntitySdtcPatient',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AssignedEntitySdtcPatient
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?II $id = null,
    ) {
    }
}
