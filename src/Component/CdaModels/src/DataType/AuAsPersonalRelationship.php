<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asPersonalRelationship',
    name: 'asPersonalRelationship',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuAsPersonalRelationship extends ANY
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@classCode')]
        public string $classCode = 'PSN',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@determinerCode')]
        public string $determinerCode = 'INSTANCE',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?II $id = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CE $administrativeGenderCode = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
