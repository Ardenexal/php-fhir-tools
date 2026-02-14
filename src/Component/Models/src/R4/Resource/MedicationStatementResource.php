<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Dosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicationStatement',
    fhirVersion: 'R4',
)]
class MedicationStatementResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfils plan, proposal or order */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var MedicationStatusCodesType|null status active | completed | entered-in-error | intended | stopped | on-hold | unknown | not-taken */
        #[NotBlank]
        public ?MedicationStatusCodesType $status = null,
        /** @var array<CodeableConcept> statusReason Reason for current status */
        public array $statusReason = [],
        /** @var CodeableConcept|null category Type of medication usage */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|Reference|null medicationX What medication was taken */
        #[NotBlank]
        public CodeableConcept|Reference|null $medicationX = null,
        /** @var Reference|null subject Who is/was taking  the medication */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null context Encounter / Episode associated with MedicationStatement */
        public ?Reference $context = null,
        /** @var DateTimePrimitive|Period|null effectiveX The date/time or interval when the medication is/was/will be taken */
        public DateTimePrimitive|Period|null $effectiveX = null,
        /** @var DateTimePrimitive|null dateAsserted When the statement was asserted? */
        public ?DateTimePrimitive $dateAsserted = null,
        /** @var Reference|null informationSource Person or organization that provided the information about the taking of this medication */
        public ?Reference $informationSource = null,
        /** @var array<Reference> derivedFrom Additional supporting information */
        public array $derivedFrom = [],
        /** @var array<CodeableConcept> reasonCode Reason for why the medication is being/was taken */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Condition or observation that supports why the medication is being/was taken */
        public array $reasonReference = [],
        /** @var array<Annotation> note Further information about the statement */
        public array $note = [],
        /** @var array<Dosage> dosage Details of how medication is/was taken or should be taken */
        public array $dosage = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
