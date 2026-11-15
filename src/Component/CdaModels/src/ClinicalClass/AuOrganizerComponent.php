<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizerComponent',
    name: 'au-OrganizerComponent',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/OrganizerComponent',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'typeCode',
        'contextConductionInd',
        'sequenceNumber',
        'sdtcPriorityNumber',
        'seperatableInd',
        'act',
        'encounter',
        'observation',
        'observationMedia',
        'organizer',
        'procedure',
        'regionOfInterest',
        'substanceAdministration',
        'supply',
    ],
)]
class AuOrganizerComponent extends OrganizerComponent
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        ?string $typeCode = null,
        bool $contextConductionInd = true,
        ?INTType $sequenceNumber = null,
        ?INTType $sdtcPriorityNumber = null,
        ?BL $seperatableInd = null,
        ?Act $act = null,
        ?Encounter $encounter = null,
        ?Observation $observation = null,
        ?ObservationMedia $observationMedia = null,
        ?Organizer $organizer = null,
        ?Procedure $procedure = null,
        ?RegionOfInterest $regionOfInterest = null,
        ?SubstanceAdministration $substanceAdministration = null,
        ?Supply $supply = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            typeCode: $typeCode,
            contextConductionInd: $contextConductionInd,
            sequenceNumber: $sequenceNumber,
            sdtcPriorityNumber: $sdtcPriorityNumber,
            seperatableInd: $seperatableInd,
            act: $act,
            encounter: $encounter,
            observation: $observation,
            observationMedia: $observationMedia,
            organizer: $organizer,
            procedure: $procedure,
            regionOfInterest: $regionOfInterest,
            substanceAdministration: $substanceAdministration,
            supply: $supply,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}
