<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
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
class FHIRMedicationAdministration extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRUri> instantiates Instantiates protocol or definition */
        public array $instantiates = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRMedicationAdministrationStatusCodesType|null status in-progress | not-done | on-hold | completed | entered-in-error | stopped | unknown */
        #[NotBlank]
        public ?FHIRMedicationAdministrationStatusCodesType $status = null,
        /** @var array<FHIRCodeableConcept> statusReason Reason administration not performed */
        public array $statusReason = [],
        /** @var FHIRCodeableConcept|null category Type of medication usage */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|FHIRReference|null medicationX What was administered */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
        /** @var FHIRReference|null subject Who received medication */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null context Encounter or Episode of Care administered as part of */
        public ?FHIRReference $context = null,
        /** @var array<FHIRReference> supportingInformation Additional information to support administration */
        public array $supportingInformation = [],
        /** @var FHIRDateTime|FHIRPeriod|null effectiveX Start and end time of administration */
        #[NotBlank]
        public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
        /** @var array<FHIRMedicationAdministrationPerformer> performer Who performed the medication administration and what they did */
        public array $performer = [],
        /** @var array<FHIRCodeableConcept> reasonCode Reason administration performed */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Condition or observation that supports why the medication was administered */
        public array $reasonReference = [],
        /** @var FHIRReference|null request Request administration performed against */
        public ?FHIRReference $request = null,
        /** @var array<FHIRReference> device Device used to administer */
        public array $device = [],
        /** @var array<FHIRAnnotation> note Information about the administration */
        public array $note = [],
        /** @var FHIRMedicationAdministrationDosage|null dosage Details of how medication was taken */
        public ?FHIRMedicationAdministrationDosage $dosage = null,
        /** @var array<FHIRReference> eventHistory A list of events of interest in the lifecycle */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
