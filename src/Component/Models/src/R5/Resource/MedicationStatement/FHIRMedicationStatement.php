<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationStatement
 *
 * @description A record of a medication that is being consumed by a patient.   A MedicationStatement may indicate that the patient may be taking the medication now or has taken the medication in the past or will be taking the medication in the future.  The source of this information can be the patient, significant other (such as a family member or spouse), or a clinician.  A common scenario where this information is captured is during the history taking process during a patient visit or stay.   The medication information may come from sources such as the patient's memory, from a prescription bottle,  or from a list of medications the patient, clinician or other party maintains.
 *
 * The primary difference between a medicationstatement and a medicationadministration is that the medication administration has complete administration information and is based on actual administration information from the person who administered the medication.  A medicationstatement is often, if not always, less specific.  There is no required date/time when the medication was administered, in fact we only know that a source has reported the patient is taking this medication, where details such as time, quantity, or rate or even medication product may be incomplete or missing or less precise.  As stated earlier, the Medication Statement information may come from the patient's memory, from a prescription bottle or from a list of medications the patient, clinician or other party maintains.  Medication administration is more formal and is not missing detailed information.
 *
 * The MedicationStatement resource was previously called MedicationStatement.
 */
#[FhirResource(
    type: 'MedicationStatement',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationStatement',
    fhirVersion: 'R5',
)]
class FHIRMedicationStatement extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRMedicationStatementStatusCodesType|null status recorded | entered-in-error | draft */
        #[NotBlank]
        public ?FHIRMedicationStatementStatusCodesType $status = null,
        /** @var array<FHIRCodeableConcept> category Type of medication statement */
        public array $category = [],
        /** @var FHIRCodeableReference|null medication What medication was taken */
        #[NotBlank]
        public ?FHIRCodeableReference $medication = null,
        /** @var FHIRReference|null subject Who is/was taking  the medication */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter associated with MedicationStatement */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null effectiveX The date/time or interval when the medication is/was/will be taken */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $effectiveX = null,
        /** @var FHIRDateTime|null dateAsserted When the usage was asserted? */
        public ?FHIRDateTime $dateAsserted = null,
        /** @var array<FHIRReference> informationSource Person or organization that provided the information about the taking of this medication */
        public array $informationSource = [],
        /** @var array<FHIRReference> derivedFrom Link to information used to derive the MedicationStatement */
        public array $derivedFrom = [],
        /** @var array<FHIRCodeableReference> reason Reason for why the medication is being/was taken */
        public array $reason = [],
        /** @var array<FHIRAnnotation> note Further information about the usage */
        public array $note = [],
        /** @var array<FHIRReference> relatedClinicalInformation Link to information relevant to the usage of a medication */
        public array $relatedClinicalInformation = [],
        /** @var FHIRMarkdown|null renderedDosageInstruction Full representation of the dosage instructions */
        public ?FHIRMarkdown $renderedDosageInstruction = null,
        /** @var array<FHIRDosage> dosage Details of how medication is/was taken or should be taken */
        public array $dosage = [],
        /** @var FHIRMedicationStatementAdherence|null adherence Indicates whether the medication is or is not being consumed or administered */
        public ?FHIRMedicationStatementAdherence $adherence = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
