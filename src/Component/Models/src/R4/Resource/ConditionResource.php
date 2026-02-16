<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
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
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Condition\ConditionEvidence;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Condition\ConditionStage;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Condition
 *
 * @description A clinical condition, problem, diagnosis, or other event, situation, issue, or clinical concept that has risen to a level of concern.
 */
#[FhirResource(type: 'Condition', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Condition', fhirVersion: 'R4')]
class ConditionResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Ids for this condition */
        public array $identifier = [],
        /** @var CodeableConcept|null clinicalStatus active | recurrence | relapse | inactive | remission | resolved */
        public ?CodeableConcept $clinicalStatus = null,
        /** @var CodeableConcept|null verificationStatus unconfirmed | provisional | differential | confirmed | refuted | entered-in-error */
        public ?CodeableConcept $verificationStatus = null,
        /** @var array<CodeableConcept> category problem-list-item | encounter-diagnosis */
        public array $category = [],
        /** @var CodeableConcept|null severity Subjective severity of condition */
        public ?CodeableConcept $severity = null,
        /** @var CodeableConcept|null code Identification of the condition, problem or diagnosis */
        public ?CodeableConcept $code = null,
        /** @var array<CodeableConcept> bodySite Anatomical location, if relevant */
        public array $bodySite = [],
        /** @var Reference|null subject Who has the condition? */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null onsetX Estimated or actual date,  date-time, or age */
        public DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null $onsetX = null,
        /** @var DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null abatementX When in resolution/remission */
        public DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null $abatementX = null,
        /** @var DateTimePrimitive|null recordedDate Date record was first recorded */
        public ?DateTimePrimitive $recordedDate = null,
        /** @var Reference|null recorder Who recorded the condition */
        public ?Reference $recorder = null,
        /** @var Reference|null asserter Person who asserts this condition */
        public ?Reference $asserter = null,
        /** @var array<ConditionStage> stage Stage/grade, usually assessed formally */
        public array $stage = [],
        /** @var array<ConditionEvidence> evidence Supporting evidence */
        public array $evidence = [],
        /** @var array<Annotation> note Additional information about the Condition */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
