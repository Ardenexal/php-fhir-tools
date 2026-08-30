<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-EntryRelationship',
    name: 'EntryRelationship',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/EntryRelationship',
)]
class AuEntryRelationship extends EntryRelationship
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/controlAct',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?AuControlAct $controlAct = null,
        ?string $typeCode = null,
        ?bool $inversionInd = null,
        ?bool $contextConductionInd = null,
        ?bool $negationInd = null,
        ?INTType $sequenceNumber = null,
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
            inversionInd: $inversionInd,
            contextConductionInd: $contextConductionInd,
            negationInd: $negationInd,
            sequenceNumber: $sequenceNumber,
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
