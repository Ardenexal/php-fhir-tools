<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ActClassObservation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Criterion',
    name: 'Criterion',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class Criterion extends InfrastructureRoot
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public ?ActClassObservation $classCode = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@moodCode')]
        public string $moodCode = 'EVN.CRT',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CD $code = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ED $text = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?BL $value = null,
    ) {
    }
}
