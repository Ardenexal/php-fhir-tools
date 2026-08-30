<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PN;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Person',
    name: 'Person',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class Person extends InfrastructureRoot
{
    /**
     * @param list<PN>                              $name
     * @param list<PersonSdtcAsPatientRelationship> $sdtcAsPatientRelationship
     * @param list<CS>                              $realmCode
     * @param list<II>                              $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public string $classCode = 'PSN',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@determinerCode')]
        public string $determinerCode = 'INSTANCE',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PN',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\PN',
        )]
        public array $name = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?ED $sdtcDesc = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Person-sdtcAsPatientRelationship',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\PersonSdtcAsPatientRelationship',
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public array $sdtcAsPatientRelationship = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
