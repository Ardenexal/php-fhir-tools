<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AppointmentResponse
 *
 * @description A reply to an appointment request for a patient and/or practitioner(s), such as a confirmation or rejection.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'AppointmentResponse',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
    fhirVersion: 'R5',
)]
class FHIRAppointmentResponse extends FHIRDomainResource
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
        /** @var FHIRReference|null appointment Appointment this response relates to */
        #[NotBlank]
        public ?\FHIRReference $appointment = null,
        /** @var FHIRBoolean|null proposedNewTime Indicator for a counter proposal */
        public ?\FHIRBoolean $proposedNewTime = null,
        /** @var FHIRInstant|null start Time from appointment, or requested new start time */
        public ?\FHIRInstant $start = null,
        /** @var FHIRInstant|null end Time from appointment, or requested new end time */
        public ?\FHIRInstant $end = null,
        /** @var array<FHIRCodeableConcept> participantType Role of participant in the appointment */
        public array $participantType = [],
        /** @var FHIRReference|null actor Person(s), Location, HealthcareService, or Device */
        public ?\FHIRReference $actor = null,
        /** @var FHIRAppointmentResponseStatusType|null participantStatus accepted | declined | tentative | needs-action | entered-in-error */
        #[NotBlank]
        public ?\FHIRAppointmentResponseStatusType $participantStatus = null,
        /** @var FHIRMarkdown|null comment Additional comments */
        public ?\FHIRMarkdown $comment = null,
        /** @var FHIRBoolean|null recurring This response is for all occurrences in a recurring request */
        public ?\FHIRBoolean $recurring = null,
        /** @var FHIRDate|null occurrenceDate Original date within a recurring request */
        public ?\FHIRDate $occurrenceDate = null,
        /** @var FHIRPositiveInt|null recurrenceId The recurrence ID of the specific recurring request */
        public ?\FHIRPositiveInt $recurrenceId = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
