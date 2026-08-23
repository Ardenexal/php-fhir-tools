<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTPOS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PN;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Patient',
    name: 'au-Patient',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuPatient extends Patient
{
    /**
     * @param list<AuAsEntityIdentifier>   $asEntityIdentifier
     * @param list<AuPersonalRelationship> $personalRelationship
     * @param list<PN>                     $name
     * @param list<CE>                     $sdtcRaceCode
     * @param list<CE>                     $sdtcEthnicGroupCode
     * @param list<Guardian>               $guardian
     * @param list<LanguageCommunication>  $languageCommunication
     * @param list<CS>                     $realmCode
     * @param list<II>                     $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?BL $multipleBirthInd = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?INTType $multipleBirthOrderNumber = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?BL $deceasedInd = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?TS $deceasedTime = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asEntityIdentifier',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEntityIdentifier',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $asEntityIdentifier = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asEmployment',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?AuAsEmployment $asEmployment = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/personalRelationship',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuPersonalRelationship',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $personalRelationship = [],
        string $classCode = 'PSN',
        string $determinerCode = 'INSTANCE',
        ?II $id = null,
        array $name = [],
        ?ED $sdtcDesc = null,
        ?CE $administrativeGenderCode = null,
        ?TS $birthTime = null,
        ?BL $sdtcDeceasedInd = null,
        ?TS $sdtcDeceasedTime = null,
        ?BL $sdtcMultipleBirthInd = null,
        ?INTPOS $sdtcMultipleBirthOrderNumber = null,
        ?CE $maritalStatusCode = null,
        ?CE $religiousAffiliationCode = null,
        ?CE $raceCode = null,
        array $sdtcRaceCode = [],
        ?CE $ethnicGroupCode = null,
        array $sdtcEthnicGroupCode = [],
        array $guardian = [],
        ?Birthplace $birthplace = null,
        array $languageCommunication = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            determinerCode: $determinerCode,
            id: $id,
            name: $name,
            sdtcDesc: $sdtcDesc,
            administrativeGenderCode: $administrativeGenderCode,
            birthTime: $birthTime,
            sdtcDeceasedInd: $sdtcDeceasedInd,
            sdtcDeceasedTime: $sdtcDeceasedTime,
            sdtcMultipleBirthInd: $sdtcMultipleBirthInd,
            sdtcMultipleBirthOrderNumber: $sdtcMultipleBirthOrderNumber,
            maritalStatusCode: $maritalStatusCode,
            religiousAffiliationCode: $religiousAffiliationCode,
            raceCode: $raceCode,
            sdtcRaceCode: $sdtcRaceCode,
            ethnicGroupCode: $ethnicGroupCode,
            sdtcEthnicGroupCode: $sdtcEthnicGroupCode,
            guardian: $guardian,
            birthplace: $birthplace,
            languageCommunication: $languageCommunication,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
