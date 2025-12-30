<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRImmunizationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Immunization
 *
 * @description Describes the event of a patient being administered a vaccine or a record of an immunization as reported by a patient, a clinician or another party.
 */
#[FhirResource(type: 'Immunization', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Immunization', fhirVersion: 'R4B')]
class FHIRImmunization extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRImmunizationStatusCodesType|null status completed | entered-in-error | not-done */
        #[NotBlank]
        public ?FHIRImmunizationStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason not done */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRCodeableConcept|null vaccineCode Vaccine product administered */
        #[NotBlank]
        public ?FHIRCodeableConcept $vaccineCode = null,
        /** @var FHIRReference|null patient Who was immunized */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRReference|null encounter Encounter immunization was part of */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRString|string|null occurrenceX Vaccine administration date */
        #[NotBlank]
        public FHIRDateTime|FHIRString|string|null $occurrenceX = null,
        /** @var FHIRDateTime|null recorded When the immunization was first captured in the subject's record */
        public ?FHIRDateTime $recorded = null,
        /** @var FHIRBoolean|null primarySource Indicates context the data was recorded in */
        public ?FHIRBoolean $primarySource = null,
        /** @var FHIRCodeableConcept|null reportOrigin Indicates the source of a secondarily reported record */
        public ?FHIRCodeableConcept $reportOrigin = null,
        /** @var FHIRReference|null location Where immunization occurred */
        public ?FHIRReference $location = null,
        /** @var FHIRReference|null manufacturer Vaccine manufacturer */
        public ?FHIRReference $manufacturer = null,
        /** @var FHIRString|string|null lotNumber Vaccine lot number */
        public FHIRString|string|null $lotNumber = null,
        /** @var FHIRDate|null expirationDate Vaccine expiration date */
        public ?FHIRDate $expirationDate = null,
        /** @var FHIRCodeableConcept|null site Body site vaccine  was administered */
        public ?FHIRCodeableConcept $site = null,
        /** @var FHIRCodeableConcept|null route How vaccine entered body */
        public ?FHIRCodeableConcept $route = null,
        /** @var FHIRQuantity|null doseQuantity Amount of vaccine administered */
        public ?FHIRQuantity $doseQuantity = null,
        /** @var array<FHIRImmunizationPerformer> performer Who performed event */
        public array $performer = [],
        /** @var array<FHIRAnnotation> note Additional immunization notes */
        public array $note = [],
        /** @var array<FHIRCodeableConcept> reasonCode Why immunization occurred */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why immunization occurred */
        public array $reasonReference = [],
        /** @var FHIRBoolean|null isSubpotent Dose potency */
        public ?FHIRBoolean $isSubpotent = null,
        /** @var array<FHIRCodeableConcept> subpotentReason Reason for being subpotent */
        public array $subpotentReason = [],
        /** @var array<FHIRImmunizationEducation> education Educational material presented to patient */
        public array $education = [],
        /** @var array<FHIRCodeableConcept> programEligibility Patient eligibility for a vaccination program */
        public array $programEligibility = [],
        /** @var FHIRCodeableConcept|null fundingSource Funding source for the vaccine */
        public ?FHIRCodeableConcept $fundingSource = null,
        /** @var array<FHIRImmunizationReaction> reaction Details of a reaction that follows immunization */
        public array $reaction = [],
        /** @var array<FHIRImmunizationProtocolApplied> protocolApplied Protocol followed by the provider */
        public array $protocolApplied = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
