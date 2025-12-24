<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Procedure
 *
 * @description An action that is or was performed on or for a patient, practitioner, device, organization, or location. For example, this can be a physical intervention on a patient like an operation, or less invasive like long term services, counseling, or hypnotherapy.  This can be a quality or safety inspection for a location, organization, or device.  This can be an accreditation procedure on a practitioner for licensing.
 */
#[FhirResource(type: 'Procedure', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Procedure', fhirVersion: 'R5')]
class FHIRProcedure extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External Identifiers for this procedure */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn A request for this procedure */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIREventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIREventStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var array<FHIRCodeableConcept> category Classification of the procedure */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Identification of the procedure */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Individual or entity the procedure was performed on */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null focus Who is the target of the procedure when it is not the subject of record only */
        public ?FHIRReference $focus = null,
        /** @var FHIRReference|null encounter The Encounter during which this Procedure was created */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRString|string|FHIRAge|FHIRRange|FHIRTiming|null occurrenceX When the procedure occurred or is occurring */
        public FHIRDateTime|FHIRPeriod|FHIRString|string|FHIRAge|FHIRRange|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRDateTime|null recorded When the procedure was first captured in the subject's record */
        public ?FHIRDateTime $recorded = null,
        /** @var FHIRReference|null recorder Who recorded the procedure */
        public ?FHIRReference $recorder = null,
        /** @var FHIRBoolean|FHIRReference|null reportedX Reported rather than primary record */
        public FHIRBoolean|FHIRReference|null $reportedX = null,
        /** @var array<FHIRProcedurePerformer> performer Who performed the procedure and what they did */
        public array $performer = [],
        /** @var FHIRReference|null location Where the procedure happened */
        public ?FHIRReference $location = null,
        /** @var array<FHIRCodeableReference> reason The justification that the procedure was performed */
        public array $reason = [],
        /** @var array<FHIRCodeableConcept> bodySite Target body sites */
        public array $bodySite = [],
        /** @var FHIRCodeableConcept|null outcome The result of procedure */
        public ?FHIRCodeableConcept $outcome = null,
        /** @var array<FHIRReference> report Any report resulting from the procedure */
        public array $report = [],
        /** @var array<FHIRCodeableReference> complication Complication following the procedure */
        public array $complication = [],
        /** @var array<FHIRCodeableConcept> followUp Instructions for follow up */
        public array $followUp = [],
        /** @var array<FHIRAnnotation> note Additional information about the procedure */
        public array $note = [],
        /** @var array<FHIRProcedureFocalDevice> focalDevice Manipulated, implanted, or removed device */
        public array $focalDevice = [],
        /** @var array<FHIRCodeableReference> used Items used during procedure */
        public array $used = [],
        /** @var array<FHIRReference> supportingInfo Extra information relevant to the procedure */
        public array $supportingInfo = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
