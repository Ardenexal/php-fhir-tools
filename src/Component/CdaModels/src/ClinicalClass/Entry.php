<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Entry',
    name: 'Entry',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'entry-only-one',
    severity: 'error',
    expression: '(act | encounter | observation | observationMedia | organizer | procedure | regionOfInterest | substanceAdministration | supply).count() = 1',
    human: 'SHALL have no more than one of act, encounter, observation, observationMedia, organizer, procedure, regionOfInterest, substanceAdministration, or supply.',
)]
class Entry extends InfrastructureRoot
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@typeCode')]
        public ?string $typeCode = null,
        #[FhirProperty(
            fhirType: 'boolean',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@contextConductionInd',
        )]
        public bool $contextConductionInd = true,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Act',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Act $act = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Encounter',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Encounter $encounter = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Observation',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Observation $observation = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ObservationMedia',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ObservationMedia $observationMedia = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Organizer',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Organizer $organizer = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Procedure',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Procedure $procedure = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/RegionOfInterest',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?RegionOfInterest $regionOfInterest = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/SubstanceAdministration',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?SubstanceAdministration $substanceAdministration = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Supply',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Supply $supply = null,
    ) {
    }
}
