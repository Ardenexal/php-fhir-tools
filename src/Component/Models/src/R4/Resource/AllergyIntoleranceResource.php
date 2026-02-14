<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCriticalityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AllergyIntolerance\AllergyIntoleranceReaction;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/AllergyIntolerance',
    fhirVersion: 'R4',
)]
class AllergyIntoleranceResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External ids for this item */
        public array $identifier = [],
        /** @var CodeableConcept|null clinicalStatus active | inactive | resolved */
        public ?CodeableConcept $clinicalStatus = null,
        /** @var CodeableConcept|null verificationStatus unconfirmed | confirmed | refuted | entered-in-error */
        public ?CodeableConcept $verificationStatus = null,
        /** @var AllergyIntoleranceTypeType|null type allergy | intolerance - Underlying mechanism (if known) */
        public ?AllergyIntoleranceTypeType $type = null,
        /** @var array<AllergyIntoleranceCategoryType> category food | medication | environment | biologic */
        public array $category = [],
        /** @var AllergyIntoleranceCriticalityType|null criticality low | high | unable-to-assess */
        public ?AllergyIntoleranceCriticalityType $criticality = null,
        /** @var CodeableConcept|null code Code that identifies the allergy or intolerance */
        public ?CodeableConcept $code = null,
        /** @var Reference|null patient Who the sensitivity is for */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null encounter Encounter when the allergy or intolerance was asserted */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null onsetX When allergy or intolerance was identified */
        public DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null $onsetX = null,
        /** @var DateTimePrimitive|null recordedDate Date first version of the resource instance was recorded */
        public ?DateTimePrimitive $recordedDate = null,
        /** @var Reference|null recorder Who recorded the sensitivity */
        public ?Reference $recorder = null,
        /** @var Reference|null asserter Source of the information about the allergy */
        public ?Reference $asserter = null,
        /** @var DateTimePrimitive|null lastOccurrence Date(/time) of last known occurrence of a reaction */
        public ?DateTimePrimitive $lastOccurrence = null,
        /** @var array<Annotation> note Additional text not captured in other fields */
        public array $note = [],
        /** @var array<AllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
        public array $reaction = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
