<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterClassHistory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterHospitalization;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterLocation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterParticipant;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterStatusHistory;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 *
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.
 */
#[FhirResource(type: 'Encounter', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R4')]
class EncounterResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifier(s) by which this encounter is known */
        public array $identifier = [],
        /** @var EncounterStatusType|null status planned | arrived | triaged | in-progress | onleave | finished | cancelled + */
        #[NotBlank]
        public ?EncounterStatusType $status = null,
        /** @var array<EncounterStatusHistory> statusHistory List of past encounter statuses */
        public array $statusHistory = [],
        /** @var Coding|null class Classification of patient encounter */
        #[NotBlank]
        public ?Coding $class = null,
        /** @var array<EncounterClassHistory> classHistory List of past encounter classes */
        public array $classHistory = [],
        /** @var array<CodeableConcept> type Specific type of encounter */
        public array $type = [],
        /** @var CodeableConcept|null serviceType Specific type of service */
        public ?CodeableConcept $serviceType = null,
        /** @var CodeableConcept|null priority Indicates the urgency of the encounter */
        public ?CodeableConcept $priority = null,
        /** @var Reference|null subject The patient or group present at the encounter */
        public ?Reference $subject = null,
        /** @var array<Reference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
        public array $episodeOfCare = [],
        /** @var array<Reference> basedOn The ServiceRequest that initiated this encounter */
        public array $basedOn = [],
        /** @var array<EncounterParticipant> participant List of participants involved in the encounter */
        public array $participant = [],
        /** @var array<Reference> appointment The appointment that scheduled this encounter */
        public array $appointment = [],
        /** @var Period|null period The start and end time of the encounter */
        public ?Period $period = null,
        /** @var Duration|null length Quantity of time the encounter lasted (less time absent) */
        public ?Duration $length = null,
        /** @var array<CodeableConcept> reasonCode Coded reason the encounter takes place */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Reason the encounter takes place (reference) */
        public array $reasonReference = [],
        /** @var array<EncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
        public array $diagnosis = [],
        /** @var array<Reference> account The set of accounts that may be used for billing for this Encounter */
        public array $account = [],
        /** @var EncounterHospitalization|null hospitalization Details about the admission to a healthcare service */
        public ?EncounterHospitalization $hospitalization = null,
        /** @var array<EncounterLocation> location List of locations where the patient has been */
        public array $location = [],
        /** @var Reference|null serviceProvider The organization (facility) responsible for this encounter */
        public ?Reference $serviceProvider = null,
        /** @var Reference|null partOf Another Encounter this encounter is part of */
        public ?Reference $partOf = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
