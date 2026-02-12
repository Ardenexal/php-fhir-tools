<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 * @see http://hl7.org/fhir/StructureDefinition/Medication
 * @description This resource is primarily used for the identification and definition of a medication for the purposes of prescribing, dispensing, and administering a medication as well as for making statements about medication use.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Medication', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Medication', fhirVersion: 'R4')]
class MedicationResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier for this medication */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Codes that identify this medication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationStatusCodesType status active | inactive | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference manufacturer Manufacturer of the item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept form powder | tablets | capsule + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $form = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio amount Amount of drug in package */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $amount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication\MedicationIngredient> ingredient Active or inactive ingredient */
		public array $ingredient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication\MedicationBatch batch Details about packaged medications */
		public ?Medication\MedicationBatch $batch = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
