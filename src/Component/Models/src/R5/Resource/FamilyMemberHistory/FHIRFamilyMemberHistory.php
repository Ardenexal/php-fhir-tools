<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFamilyHistoryStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
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
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/FamilyMemberHistory',
    fhirVersion: 'R5',
)]
class FHIRFamilyMemberHistory extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Id(s) for this record */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var FHIRFamilyHistoryStatusType|null status partial | completed | entered-in-error | health-unknown */
        #[NotBlank]
        public ?FHIRFamilyHistoryStatusType $status = null,
        /** @var FHIRCodeableConcept|null dataAbsentReason subject-unknown | withheld | unable-to-obtain | deferred */
        public ?FHIRCodeableConcept $dataAbsentReason = null,
        /** @var FHIRReference|null patient Patient history is about */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRDateTime|null date When history was recorded or last updated */
        public ?FHIRDateTime $date = null,
        /** @var array<FHIRFamilyMemberHistoryParticipant> participant Who or what participated in the activities related to the family member history and how they were involved */
        public array $participant = [],
        /** @var FHIRString|string|null name The family member described */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null relationship Relationship to the subject */
        #[NotBlank]
        public ?FHIRCodeableConcept $relationship = null,
        /** @var FHIRCodeableConcept|null sex male | female | other | unknown */
        public ?FHIRCodeableConcept $sex = null,
        /** @var FHIRPeriod|FHIRDate|FHIRString|string|null bornX (approximate) date of birth */
        public FHIRPeriod|FHIRDate|FHIRString|string|null $bornX = null,
        /** @var FHIRAge|FHIRRange|FHIRString|string|null ageX (approximate) age */
        public FHIRAge|FHIRRange|FHIRString|string|null $ageX = null,
        /** @var FHIRBoolean|null estimatedAge Age is estimated? */
        public ?FHIRBoolean $estimatedAge = null,
        /** @var FHIRBoolean|FHIRAge|FHIRRange|FHIRDate|FHIRString|string|null deceasedX Dead? How old/when? */
        public FHIRBoolean|FHIRAge|FHIRRange|FHIRDate|FHIRString|string|null $deceasedX = null,
        /** @var array<FHIRCodeableReference> reason Why was family member history performed? */
        public array $reason = [],
        /** @var array<FHIRAnnotation> note General note about related person */
        public array $note = [],
        /** @var array<FHIRFamilyMemberHistoryCondition> condition Condition that the related person had */
        public array $condition = [],
        /** @var array<FHIRFamilyMemberHistoryProcedure> procedure Procedures that the related person had */
        public array $procedure = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
