<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Procedure
 *
 * @description An action that is or was performed on or for a patient. This can be a physical intervention like an operation, or less invasive like long term services, counseling, or hypnotherapy.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Procedure', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Procedure', fhirVersion: 'R4B')]
class FHIRProcedure extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
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
        public ?\FHIREventStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?\FHIRCodeableConcept $statusReason = null,
        /** @var FHIRCodeableConcept|null category Classification of the procedure */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null code Identification of the procedure */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who the procedure was performed on */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRString|string|FHIRAge|FHIRRange|null performedX When the procedure was performed */
        public \FHIRDateTime|\FHIRPeriod|\FHIRString|string|\FHIRAge|\FHIRRange|null $performedX = null,
        /** @var FHIRReference|null recorder Who recorded the procedure */
        public ?\FHIRReference $recorder = null,
        /** @var FHIRReference|null asserter Person who asserts this procedure */
        public ?\FHIRReference $asserter = null,
        /** @var array<FHIRProcedurePerformer> performer The people who performed the procedure */
        public array $performer = [],
        /** @var FHIRReference|null location Where the procedure happened */
        public ?\FHIRReference $location = null,
        /** @var array<FHIRCodeableConcept> reasonCode Coded reason procedure performed */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference The justification that the procedure was performed */
        public array $reasonReference = [],
        /** @var array<FHIRCodeableConcept> bodySite Target body sites */
        public array $bodySite = [],
        /** @var FHIRCodeableConcept|null outcome The result of procedure */
        public ?\FHIRCodeableConcept $outcome = null,
        /** @var array<FHIRReference> report Any report resulting from the procedure */
        public array $report = [],
        /** @var array<FHIRCodeableConcept> complication Complication following the procedure */
        public array $complication = [],
        /** @var array<FHIRReference> complicationDetail A condition that is a result of the procedure */
        public array $complicationDetail = [],
        /** @var array<FHIRCodeableConcept> followUp Instructions for follow up */
        public array $followUp = [],
        /** @var array<FHIRAnnotation> note Additional information about the procedure */
        public array $note = [],
        /** @var array<FHIRProcedureFocalDevice> focalDevice Manipulated, implanted, or removed device */
        public array $focalDevice = [],
        /** @var array<FHIRReference> usedReference Items used during procedure */
        public array $usedReference = [],
        /** @var array<FHIRCodeableConcept> usedCode Coded items used during the procedure */
        public array $usedCode = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
