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
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Slot
 *
 * @description A slot of time on a schedule that may be available for booking appointments.
 */
#[FhirResource(type: 'Slot', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Slot', fhirVersion: 'R4B')]
class FHIRSlot extends FHIRDomainResource
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
        /** @var array<FHIRCodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        public array $serviceCategory = [],
        /** @var array<FHIRCodeableConcept> serviceType The type of appointments that can be booked into this slot (ideally this would be an identifiable service - which is at a location, rather than the location itself). If provided then this overrides the value provided on the availability resource */
        public array $serviceType = [],
        /** @var array<FHIRCodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        public array $specialty = [],
        /** @var FHIRCodeableConcept|null appointmentType The style of appointment or patient that may be booked in the slot (not service type) */
        public ?FHIRCodeableConcept $appointmentType = null,
        /** @var FHIRReference|null schedule The schedule resource that this slot defines an interval of status information */
        #[NotBlank]
        public ?FHIRReference $schedule = null,
        /** @var FHIRSlotStatusType|null status busy | free | busy-unavailable | busy-tentative | entered-in-error */
        #[NotBlank]
        public ?FHIRSlotStatusType $status = null,
        /** @var FHIRInstant|null start Date/Time that the slot is to begin */
        #[NotBlank]
        public ?FHIRInstant $start = null,
        /** @var FHIRInstant|null end Date/Time that the slot is to conclude */
        #[NotBlank]
        public ?FHIRInstant $end = null,
        /** @var FHIRBoolean|null overbooked This slot has already been overbooked, appointments are unlikely to be accepted for this time */
        public ?FHIRBoolean $overbooked = null,
        /** @var FHIRString|string|null comment Comments on the slot to describe any extended information. Such as custom constraints on the slot */
        public FHIRString|string|null $comment = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
