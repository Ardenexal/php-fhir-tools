<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EventStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Procedure\ProcedureFocalDevice;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Procedure\ProcedurePerformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Procedure
 *
 * @description An action that is or was performed on or for a patient. This can be a physical intervention like an operation, or less invasive like long term services, counseling, or hypnotherapy.
 */
#[FhirResource(type: 'Procedure', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Procedure', fhirVersion: 'R4')]
class ProcedureResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Identifiers for this procedure */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn A request for this procedure */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var EventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?EventStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var CodeableConcept|null category Classification of the procedure */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null code Identification of the procedure */
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who the procedure was performed on */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|StringPrimitive|string|Age|Range|null performedX When the procedure was performed */
        public DateTimePrimitive|Period|StringPrimitive|string|Age|Range|null $performedX = null,
        /** @var Reference|null recorder Who recorded the procedure */
        public ?Reference $recorder = null,
        /** @var Reference|null asserter Person who asserts this procedure */
        public ?Reference $asserter = null,
        /** @var array<ProcedurePerformer> performer The people who performed the procedure */
        public array $performer = [],
        /** @var Reference|null location Where the procedure happened */
        public ?Reference $location = null,
        /** @var array<CodeableConcept> reasonCode Coded reason procedure performed */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference The justification that the procedure was performed */
        public array $reasonReference = [],
        /** @var array<CodeableConcept> bodySite Target body sites */
        public array $bodySite = [],
        /** @var CodeableConcept|null outcome The result of procedure */
        public ?CodeableConcept $outcome = null,
        /** @var array<Reference> report Any report resulting from the procedure */
        public array $report = [],
        /** @var array<CodeableConcept> complication Complication following the procedure */
        public array $complication = [],
        /** @var array<Reference> complicationDetail A condition that is a result of the procedure */
        public array $complicationDetail = [],
        /** @var array<CodeableConcept> followUp Instructions for follow up */
        public array $followUp = [],
        /** @var array<Annotation> note Additional information about the procedure */
        public array $note = [],
        /** @var array<ProcedureFocalDevice> focalDevice Manipulated, implanted, or removed device */
        public array $focalDevice = [],
        /** @var array<Reference> usedReference Items used during procedure */
        public array $usedReference = [],
        /** @var array<CodeableConcept> usedCode Coded items used during the procedure */
        public array $usedCode = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
