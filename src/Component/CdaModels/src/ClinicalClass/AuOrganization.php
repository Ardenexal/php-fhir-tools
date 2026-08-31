<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ON;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Organization',
    name: 'au-Organization',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Organization',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'determinerCode',
        'id',
        'name',
        'telecom',
        'addr',
        'standardIndustryClassCode',
        'asOrganizationPartOf',
        'asEntityIdentifier',
    ],
)]
class AuOrganization extends Organization
{
    /**
     * @param list<AuAsEntityIdentifier> $asEntityIdentifier
     * @param list<II>                   $id
     * @param list<ON>                   $name
     * @param list<TEL>                  $telecom
     * @param list<AD>                   $addr
     * @param list<CS>                   $realmCode
     * @param list<II>                   $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asEntityIdentifier',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEntityIdentifier',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $asEntityIdentifier = [],
        string $classCode = 'ORG',
        string $determinerCode = 'INSTANCE',
        array $id = [],
        array $name = [],
        array $telecom = [],
        array $addr = [],
        ?CE $standardIndustryClassCode = null,
        ?OrganizationPartOf $asOrganizationPartOf = null,
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
            telecom: $telecom,
            addr: $addr,
            standardIndustryClassCode: $standardIndustryClassCode,
            asOrganizationPartOf: $asOrganizationPartOf,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
