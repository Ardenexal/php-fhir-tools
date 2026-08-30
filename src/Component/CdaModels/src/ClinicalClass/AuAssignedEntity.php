<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-AssignedEntity',
    name: 'au-AssignedEntity',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/AssignedEntity',
)]
class AuAssignedEntity extends AssignedEntity
{
    /**
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     * @param list<CE>           $sdtcSpecialty
     * @param list<AD>           $addr
     * @param list<TEL>          $telecom
     * @param list<CS>           $realmCode
     * @param list<II>           $templateId
     */
    public function __construct(
        string $classCode = 'ASSIGNED',
        array $id = [],
        array $sdtcIdentifiedBy = [],
        ?CE $code = null,
        array $sdtcSpecialty = [],
        array $addr = [],
        array $telecom = [],
        ?Person $assignedPerson = null,
        ?Organization $representedOrganization = null,
        ?AssignedEntitySdtcPatient $sdtcPatient = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            id: $id,
            sdtcIdentifiedBy: $sdtcIdentifiedBy,
            code: $code,
            sdtcSpecialty: $sdtcSpecialty,
            addr: $addr,
            telecom: $telecom,
            assignedPerson: $assignedPerson,
            representedOrganization: $representedOrganization,
            sdtcPatient: $sdtcPatient,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
