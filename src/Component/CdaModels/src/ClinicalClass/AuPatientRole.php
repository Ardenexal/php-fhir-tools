<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-PatientRole',
    name: 'au-PatientRole',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/PatientRole',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'id',
        'sdtcIdentifiedBy',
        'addr',
        'telecom',
        'patient',
        'providerOrganization',
    ],
)]
class AuPatientRole extends PatientRole
{
    /**
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     * @param list<AD>           $addr
     * @param list<TEL>          $telecom
     * @param list<CS>           $realmCode
     * @param list<II>           $templateId
     */
    public function __construct(
        string $classCode = 'PAT',
        array $id = [],
        array $sdtcIdentifiedBy = [],
        array $addr = [],
        array $telecom = [],
        ?Patient $patient = null,
        ?Organization $providerOrganization = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            id: $id,
            sdtcIdentifiedBy: $sdtcIdentifiedBy,
            addr: $addr,
            telecom: $telecom,
            patient: $patient,
            providerOrganization: $providerOrganization,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
