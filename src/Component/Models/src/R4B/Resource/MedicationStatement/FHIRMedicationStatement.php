<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMedicationStatementStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicationStatement
 *
 * @description A record of a medication that is being consumed by a patient.   A MedicationStatement may indicate that the patient may be taking the medication now or has taken the medication in the past or will be taking the medication in the future.  The source of this information can be the patient, significant other (such as a family member or spouse), or a clinician.  A common scenario where this information is captured is during the history taking process during a patient visit or stay.   The medication information may come from sources such as the patient's memory, from a prescription bottle,  or from a list of medications the patient, clinician or other party maintains.
 *
 * The primary difference between a medication statement and a medication administration is that the medication administration has complete administration information and is based on actual administration information from the person who administered the medication.  A medication statement is often, if not always, less specific.  There is no required date/time when the medication was administered, in fact we only know that a source has reported the patient is taking this medication, where details such as time, quantity, or rate or even medication product may be incomplete or missing or less precise.  As stated earlier, the medication statement information may come from the patient's memory, from a prescription bottle or from a list of medications the patient, clinician or other party maintains.  Medication administration is more formal and is not missing detailed information.
 */
#[FhirResource(
    type: 'MedicationStatement',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationStatement',
    fhirVersion: 'R4B',
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
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Fulfils plan, proposal or order */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRMedicationStatementStatusCodesType|null status active | completed | entered-in-error | intended | stopped | on-hold | unknown | not-taken */
        #[NotBlank]
        public ?FHIRMedicationStatementStatusCodesType $status = null,
        /** @var array<FHIRCodeableConcept> statusReason Reason for current status */
        public array $statusReason = [],
        /** @var FHIRCodeableConcept|null category Type of medication usage */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|FHIRReference|null medicationX What medication was taken */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
        /** @var FHIRReference|null subject Who is/was taking  the medication */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null context Encounter / Episode associated with MedicationStatement */
        public ?FHIRReference $context = null,
        /** @var FHIRDateTime|FHIRPeriod|null effectiveX The date/time or interval when the medication is/was/will be taken */
        public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
        /** @var FHIRDateTime|null dateAsserted When the statement was asserted? */
        public ?FHIRDateTime $dateAsserted = null,
        /** @var FHIRReference|null informationSource Person or organization that provided the information about the taking of this medication */
        public ?FHIRReference $informationSource = null,
        /** @var array<FHIRReference> derivedFrom Additional supporting information */
        public array $derivedFrom = [],
        /** @var array<FHIRCodeableConcept> reasonCode Reason for why the medication is being/was taken */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Condition or observation that supports why the medication is being/was taken */
        public array $reasonReference = [],
        /** @var array<FHIRAnnotation> note Further information about the statement */
        public array $note = [],
        /** @var array<FHIRDosage> dosage Details of how medication is/was taken or should be taken */
        public array $dosage = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
