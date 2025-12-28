<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Schedule
 *
 * @description A container for slots of time that may be available for booking appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Schedule', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Schedule', fhirVersion: 'R5')]
class FHIRSchedule extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External Ids for this item */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this schedule is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var array<FHIRCodeableConcept> serviceCategory High-level category */
        public array $serviceCategory = [],
        /** @var array<FHIRCodeableReference> serviceType Specific service */
        public array $serviceType = [],
        /** @var array<FHIRCodeableConcept> specialty Type of specialty needed */
        public array $specialty = [],
        /** @var FHIRString|string|null name Human-readable label */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRReference> actor Resource(s) that availability information is being provided for */
        public array $actor = [],
        /** @var FHIRPeriod|null planningHorizon Period of time covered by schedule */
        public ?\FHIRPeriod $planningHorizon = null,
        /** @var FHIRMarkdown|null comment Comments on availability */
        public ?\FHIRMarkdown $comment = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
