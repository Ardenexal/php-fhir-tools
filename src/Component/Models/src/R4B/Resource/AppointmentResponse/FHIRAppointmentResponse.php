<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AppointmentResponse
 *
 * @description A reply to an appointment request for a patient and/or practitioner(s), such as a confirmation or rejection.
 */
#[FhirResource(
    type: 'AppointmentResponse',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
    fhirVersion: 'R4B',
)]
class FHIRAppointmentResponse extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External Ids for this item */
        public array $identifier = [],
        /** @var FHIRReference|null appointment Appointment this response relates to */
        #[NotBlank]
        public ?FHIRReference $appointment = null,
        /** @var FHIRInstant|null start Time from appointment, or requested new start time */
        public ?FHIRInstant $start = null,
        /** @var FHIRInstant|null end Time from appointment, or requested new end time */
        public ?FHIRInstant $end = null,
        /** @var array<FHIRCodeableConcept> participantType Role of participant in the appointment */
        public array $participantType = [],
        /** @var FHIRReference|null actor Person, Location, HealthcareService, or Device */
        public ?FHIRReference $actor = null,
        /** @var FHIRParticipationStatusType|null participantStatus accepted | declined | tentative | needs-action */
        #[NotBlank]
        public ?FHIRParticipationStatusType $participantStatus = null,
        /** @var FHIRString|string|null comment Additional comments */
        public FHIRString|string|null $comment = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
