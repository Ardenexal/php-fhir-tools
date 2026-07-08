<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuAddress;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuOrganizationName;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuTelecom;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Entity',
    name: 'au-Entity',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuEntity extends Entity
{
    /**
     * @param list<AuOrganizationName>     $name
     * @param list<AuTelecom>              $telecom
     * @param list<AuAddress>              $addr
     * @param list<AuOrganizationPartOf>   $asOrganizationPartOf
     * @param list<AuAsEntityIdentifier>   $asEntityIdentifier
     * @param list<AuPersonalRelationship> $personalRelationship
     * @param list<II>                     $id
     * @param list<CS>                     $realmCode
     * @param list<II>                     $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationName',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\AuOrganizationName',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $name = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Telecom',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\AuTelecom',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $telecom = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Address',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\AuAddress',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $addr = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationPartOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuOrganizationPartOf',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $asOrganizationPartOf = [],
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
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asQualifiedEntity',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?AuAsQualifiedEntity $asQualifiedEntity = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/personalRelationship',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuPersonalRelationship',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $personalRelationship = [],
        ?string $classCode = null,
        string $determinerCode = 'INSTANCE',
        array $id = [],
        ?CE $code = null,
        ?ED $desc = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            determinerCode: $determinerCode,
            id: $id,
            code: $code,
            desc: $desc,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
