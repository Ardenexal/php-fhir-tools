<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SlotStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Slot
 *
 * @description A slot of time on a schedule that may be available for booking appointments.
 */
#[FhirResource(type: 'Slot', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Slot', fhirVersion: 'R4')]
class SlotResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External Ids for this item */
        public array $identifier = [],
        /** @var array<CodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        public array $serviceCategory = [],
        /** @var array<CodeableConcept> serviceType The type of appointments that can be booked into this slot (ideally this would be an identifiable service - which is at a location, rather than the location itself). If provided then this overrides the value provided on the availability resource */
        public array $serviceType = [],
        /** @var array<CodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        public array $specialty = [],
        /** @var CodeableConcept|null appointmentType The style of appointment or patient that may be booked in the slot (not service type) */
        public ?CodeableConcept $appointmentType = null,
        /** @var Reference|null schedule The schedule resource that this slot defines an interval of status information */
        #[NotBlank]
        public ?Reference $schedule = null,
        /** @var SlotStatusType|null status busy | free | busy-unavailable | busy-tentative | entered-in-error */
        #[NotBlank]
        public ?SlotStatusType $status = null,
        /** @var InstantPrimitive|null start Date/Time that the slot is to begin */
        #[NotBlank]
        public ?InstantPrimitive $start = null,
        /** @var InstantPrimitive|null end Date/Time that the slot is to conclude */
        #[NotBlank]
        public ?InstantPrimitive $end = null,
        /** @var bool|null overbooked This slot has already been overbooked, appointments are unlikely to be accepted for this time */
        public ?bool $overbooked = null,
        /** @var StringPrimitive|string|null comment Comments on the slot to describe any extended information. Such as custom constraints on the slot */
        public StringPrimitive|string|null $comment = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
