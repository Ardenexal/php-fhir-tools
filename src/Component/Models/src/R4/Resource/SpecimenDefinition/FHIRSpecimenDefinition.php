<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SpecimenDefinition
 *
 * @description A kind of specimen with associated set of requirements.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SpecimenDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SpecimenDefinition',
    fhirVersion: 'R4',
)]
class FHIRSpecimenDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Business identifier of a kind of specimen */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null typeCollected Kind of material to collect */
        public ?\FHIRCodeableConcept $typeCollected = null,
        /** @var array<FHIRCodeableConcept> patientPreparation Patient preparation for collection */
        public array $patientPreparation = [],
        /** @var FHIRString|string|null timeAspect Time aspect for collection */
        public \FHIRString|string|null $timeAspect = null,
        /** @var array<FHIRCodeableConcept> collection Specimen collection procedure */
        public array $collection = [],
        /** @var array<FHIRSpecimenDefinitionTypeTested> typeTested Specimen in container intended for testing by lab */
        public array $typeTested = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
