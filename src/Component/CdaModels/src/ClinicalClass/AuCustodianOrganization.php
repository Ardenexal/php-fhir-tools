<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ON;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-CustodianOrganization',
    name: 'au-CustodianOrganization',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuCustodianOrganization extends CustodianOrganization
{
    /**
     * @param list<AuAsEntityIdentifier> $asEntityIdentifier
     * @param list<II>                   $id
     * @param list<TEL>                  $sdtcTelecom
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
        ?ON $name = null,
        ?TEL $telecom = null,
        array $sdtcTelecom = [],
        ?AD $addr = null,
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
            sdtcTelecom: $sdtcTelecom,
            addr: $addr,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
