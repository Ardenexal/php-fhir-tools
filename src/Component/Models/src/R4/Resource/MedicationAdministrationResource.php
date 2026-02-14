<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationAdministrationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationAdministration\MedicationAdministrationDosage;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationAdministration\MedicationAdministrationPerformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationAdministration
 *
 * @description Describes the event of a patient consuming or otherwise being administered a medication.  This may be as simple as swallowing a tablet or it may be a long running infusion.  Related resources tie this event to the authorizing prescription, and the specific encounter between patient and health care practitioner.
 */
#[FhirResource(
    type: 'MedicationAdministration',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationAdministration',
    fhirVersion: 'R4',
)]
class MedicationAdministrationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<UriPrimitive> instantiates Instantiates protocol or definition */
        public array $instantiates = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var MedicationAdministrationStatusCodesType|null status in-progress | not-done | on-hold | completed | entered-in-error | stopped | unknown */
        #[NotBlank]
        public ?MedicationAdministrationStatusCodesType $status = null,
        /** @var array<CodeableConcept> statusReason Reason administration not performed */
        public array $statusReason = [],
        /** @var CodeableConcept|null category Type of medication usage */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|Reference|null medicationX What was administered */
        #[NotBlank]
        public CodeableConcept|Reference|null $medicationX = null,
        /** @var Reference|null subject Who received medication */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null context Encounter or Episode of Care administered as part of */
        public ?Reference $context = null,
        /** @var array<Reference> supportingInformation Additional information to support administration */
        public array $supportingInformation = [],
        /** @var DateTimePrimitive|Period|null effectiveX Start and end time of administration */
        #[NotBlank]
        public DateTimePrimitive|Period|null $effectiveX = null,
        /** @var array<MedicationAdministrationPerformer> performer Who performed the medication administration and what they did */
        public array $performer = [],
        /** @var array<CodeableConcept> reasonCode Reason administration performed */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Condition or observation that supports why the medication was administered */
        public array $reasonReference = [],
        /** @var Reference|null request Request administration performed against */
        public ?Reference $request = null,
        /** @var array<Reference> device Device used to administer */
        public array $device = [],
        /** @var array<Annotation> note Information about the administration */
        public array $note = [],
        /** @var MedicationAdministrationDosage|null dosage Details of how medication was taken */
        public ?MedicationAdministrationDosage $dosage = null,
        /** @var array<Reference> eventHistory A list of events of interest in the lifecycle */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
