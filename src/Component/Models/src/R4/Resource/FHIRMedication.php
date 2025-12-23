<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/Medication
 * @description This resource is primarily used for the identification and definition of a medication for the purposes of prescribing, dispensing, and administering a medication as well as for making statements about medication use.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Medication', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Medication', fhirVersion: 'R4')]
class FHIRMedication extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Business identifier for this medication */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code Codes that identify this medication */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationStatusCodesType status active | inactive | entered-in-error */
		public ?FHIRMedicationStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference manufacturer Manufacturer of the item */
		public ?FHIRReference $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept form powder | tablets | capsule + */
		public ?FHIRCodeableConcept $form = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio amount Amount of drug in package */
		public ?FHIRRatio $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationBatch batch Details about packaged medications */
		public ?FHIRMedicationBatch $batch = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
