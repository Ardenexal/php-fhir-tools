<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-EncounterParticipant',
    name: 'au-EncounterParticipant',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuEncounterParticipant extends EncounterParticipant
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        ?string $typeCode = null,
        ?IVLTS $time = null,
        ?AssignedEntity $assignedEntity = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            typeCode: $typeCode,
            time: $time,
            assignedEntity: $assignedEntity,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
