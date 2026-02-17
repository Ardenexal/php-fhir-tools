<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FamilyHistoryStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FamilyMemberHistory\FamilyMemberHistoryCondition;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/FamilyMemberHistory
 *
 * @description Significant health conditions for a person related to the patient relevant in the context of care for the patient.
 */
#[FhirResource(
    type: 'FamilyMemberHistory',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/FamilyMemberHistory',
    fhirVersion: 'R4',
)]
class FamilyMemberHistoryResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Id(s) for this record */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var FamilyHistoryStatusType|null status partial | completed | entered-in-error | health-unknown */
        #[NotBlank]
        public ?FamilyHistoryStatusType $status = null,
        /** @var CodeableConcept|null dataAbsentReason subject-unknown | withheld | unable-to-obtain | deferred */
        public ?CodeableConcept $dataAbsentReason = null,
        /** @var Reference|null patient Patient history is about */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var DateTimePrimitive|null date When history was recorded or last updated */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null name The family member described */
        public StringPrimitive|string|null $name = null,
        /** @var CodeableConcept|null relationship Relationship to the subject */
        #[NotBlank]
        public ?CodeableConcept $relationship = null,
        /** @var CodeableConcept|null sex male | female | other | unknown */
        public ?CodeableConcept $sex = null,
        /** @var Period|DatePrimitive|StringPrimitive|string|null bornX (approximate) date of birth */
        public Period|DatePrimitive|StringPrimitive|string|null $bornX = null,
        /** @var Age|Range|StringPrimitive|string|null ageX (approximate) age */
        public Age|Range|StringPrimitive|string|null $ageX = null,
        /** @var bool|null estimatedAge Age is estimated? */
        public ?bool $estimatedAge = null,
        /** @var bool|Age|Range|DatePrimitive|StringPrimitive|string|null deceasedX Dead? How old/when? */
        public bool|Age|Range|DatePrimitive|StringPrimitive|string|null $deceasedX = null,
        /** @var array<CodeableConcept> reasonCode Why was family member history performed? */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why was family member history performed? */
        public array $reasonReference = [],
        /** @var array<Annotation> note General note about related person */
        public array $note = [],
        /** @var array<FamilyMemberHistoryCondition> condition Condition that the related person had */
        public array $condition = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
