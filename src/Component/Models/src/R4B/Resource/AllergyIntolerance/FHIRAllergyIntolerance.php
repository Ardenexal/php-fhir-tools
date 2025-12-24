<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AllergyIntolerance
 *
 * @description Risk of harmful or undesirable, physiological response which is unique to an individual and associated with exposure to a substance.
 */
#[FhirResource(
    type: 'AllergyIntolerance',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AllergyIntolerance',
    fhirVersion: 'R4B',
)]
class FHIRAllergyIntolerance extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External ids for this item */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null clinicalStatus active | inactive | resolved */
        public ?FHIRCodeableConcept $clinicalStatus = null,
        /** @var FHIRCodeableConcept|null verificationStatus unconfirmed | confirmed | refuted | entered-in-error */
        public ?FHIRCodeableConcept $verificationStatus = null,
        /** @var FHIRAllergyIntoleranceTypeType|null type allergy | intolerance - Underlying mechanism (if known) */
        public ?FHIRAllergyIntoleranceTypeType $type = null,
        /** @var array<FHIRAllergyIntoleranceCategoryType> category food | medication | environment | biologic */
        public array $category = [],
        /** @var FHIRAllergyIntoleranceCriticalityType|null criticality low | high | unable-to-assess */
        public ?FHIRAllergyIntoleranceCriticalityType $criticality = null,
        /** @var FHIRCodeableConcept|null code Code that identifies the allergy or intolerance */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null patient Who the sensitivity is for */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRReference|null encounter Encounter when the allergy or intolerance was asserted */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRString|string|null onsetX When allergy or intolerance was identified */
        public FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRString|string|null $onsetX = null,
        /** @var FHIRDateTime|null recordedDate Date first version of the resource instance was recorded */
        public ?FHIRDateTime $recordedDate = null,
        /** @var FHIRReference|null recorder Who recorded the sensitivity */
        public ?FHIRReference $recorder = null,
        /** @var FHIRReference|null asserter Source of the information about the allergy */
        public ?FHIRReference $asserter = null,
        /** @var FHIRDateTime|null lastOccurrence Date(/time) of last known occurrence of a reaction */
        public ?FHIRDateTime $lastOccurrence = null,
        /** @var array<FHIRAnnotation> note Additional text not captured in other fields */
        public array $note = [],
        /** @var array<FHIRAllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
        public array $reaction = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
