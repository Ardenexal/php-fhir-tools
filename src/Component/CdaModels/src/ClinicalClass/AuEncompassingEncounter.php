<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-EncompassingEncounter',
    name: 'au-EncompassingEncounter',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/EncompassingEncounter',
)]
class AuEncompassingEncounter extends EncompassingEncounter
{
    /**
     * @param list<II>                   $id
     * @param list<EncounterParticipant> $encounterParticipant
     * @param list<CS>                   $realmCode
     * @param list<II>                   $templateId
     */
    public function __construct(
        string $classCode = 'ENC',
        string $moodCode = 'EVN',
        array $id = [],
        ?CE $code = null,
        ?IVLTS $effectiveTime = null,
        ?CE $sdtcAdmissionReferralSourceCode = null,
        ?CE $dischargeDispositionCode = null,
        ?EncompassingEncounterResponsibleParty $responsibleParty = null,
        array $encounterParticipant = [],
        ?EncompassingEncounterLocation $location = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            id: $id,
            code: $code,
            effectiveTime: $effectiveTime,
            sdtcAdmissionReferralSourceCode: $sdtcAdmissionReferralSourceCode,
            dischargeDispositionCode: $dischargeDispositionCode,
            responsibleParty: $responsibleParty,
            encounterParticipant: $encounterParticipant,
            location: $location,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
