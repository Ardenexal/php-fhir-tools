<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ImmunizationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationEducation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationPerformer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationProtocolApplied;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization\ImmunizationReaction;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Immunization
 *
 * @description Describes the event of a patient being administered a vaccine or a record of an immunization as reported by a patient, a clinician or another party.
 */
#[FhirResource(type: 'Immunization', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Immunization', fhirVersion: 'R4')]
class ImmunizationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var ImmunizationStatusCodesType|null status completed | entered-in-error | not-done */
        #[NotBlank]
        public ?ImmunizationStatusCodesType $status = null,
        /** @var CodeableConcept|null statusReason Reason not done */
        public ?CodeableConcept $statusReason = null,
        /** @var CodeableConcept|null vaccineCode Vaccine product administered */
        #[NotBlank]
        public ?CodeableConcept $vaccineCode = null,
        /** @var Reference|null patient Who was immunized */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null encounter Encounter immunization was part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|StringPrimitive|string|null occurrenceX Vaccine administration date */
        #[NotBlank]
        public DateTimePrimitive|StringPrimitive|string|null $occurrenceX = null,
        /** @var DateTimePrimitive|null recorded When the immunization was first captured in the subject's record */
        public ?DateTimePrimitive $recorded = null,
        /** @var bool|null primarySource Indicates context the data was recorded in */
        public ?bool $primarySource = null,
        /** @var CodeableConcept|null reportOrigin Indicates the source of a secondarily reported record */
        public ?CodeableConcept $reportOrigin = null,
        /** @var Reference|null location Where immunization occurred */
        public ?Reference $location = null,
        /** @var Reference|null manufacturer Vaccine manufacturer */
        public ?Reference $manufacturer = null,
        /** @var StringPrimitive|string|null lotNumber Vaccine lot number */
        public StringPrimitive|string|null $lotNumber = null,
        /** @var DatePrimitive|null expirationDate Vaccine expiration date */
        public ?DatePrimitive $expirationDate = null,
        /** @var CodeableConcept|null site Body site vaccine  was administered */
        public ?CodeableConcept $site = null,
        /** @var CodeableConcept|null route How vaccine entered body */
        public ?CodeableConcept $route = null,
        /** @var Quantity|null doseQuantity Amount of vaccine administered */
        public ?Quantity $doseQuantity = null,
        /** @var array<ImmunizationPerformer> performer Who performed event */
        public array $performer = [],
        /** @var array<Annotation> note Additional immunization notes */
        public array $note = [],
        /** @var array<CodeableConcept> reasonCode Why immunization occurred */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why immunization occurred */
        public array $reasonReference = [],
        /** @var bool|null isSubpotent Dose potency */
        public ?bool $isSubpotent = null,
        /** @var array<CodeableConcept> subpotentReason Reason for being subpotent */
        public array $subpotentReason = [],
        /** @var array<ImmunizationEducation> education Educational material presented to patient */
        public array $education = [],
        /** @var array<CodeableConcept> programEligibility Patient eligibility for a vaccination program */
        public array $programEligibility = [],
        /** @var CodeableConcept|null fundingSource Funding source for the vaccine */
        public ?CodeableConcept $fundingSource = null,
        /** @var array<ImmunizationReaction> reaction Details of a reaction that follows immunization */
        public array $reaction = [],
        /** @var array<ImmunizationProtocolApplied> protocolApplied Protocol followed by the provider */
        public array $protocolApplied = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
