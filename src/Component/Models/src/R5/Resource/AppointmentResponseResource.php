<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AppointmentResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
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
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
    fhirVersion: 'R5',
)]
class AppointmentResponseResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External Ids for this item */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var Reference|null appointment Appointment this response relates to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $appointment = null,
        /** @var bool|null proposedNewTime Indicator for a counter proposal */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $proposedNewTime = null,
        /** @var InstantPrimitive|null start Time from appointment, or requested new start time */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $start = null,
        /** @var InstantPrimitive|null end Time from appointment, or requested new end time */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $end = null,
        /** @var array<CodeableConcept> participantType Role of participant in the appointment */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $participantType = [],
        /** @var Reference|null actor Person(s), Location, HealthcareService, or Device */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $actor = null,
        /** @var AppointmentResponseStatusType|null participantStatus accepted | declined | tentative | needs-action | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?AppointmentResponseStatusType $participantStatus = null,
        /** @var MarkdownPrimitive|null comment Additional comments */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $comment = null,
        /** @var bool|null recurring This response is for all occurrences in a recurring request */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $recurring = null,
        /** @var DatePrimitive|null occurrenceDate Original date within a recurring request */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $occurrenceDate = null,
        /** @var PositiveIntPrimitive|null recurrenceId The recurrence ID of the specific recurring request */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $recurrenceId = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
