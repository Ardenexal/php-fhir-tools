<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductManufactured
 * @description The manufactured item as contained in the packaged medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductManufactured',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductManufactured',
	fhirVersion: 'R4B',
)]
class FHIRMedicinalProductManufactured extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept manufacturedDoseForm Dose form as manufactured and before any transformation into the pharmaceutical product */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $manufacturedDoseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept unitOfPresentation The “real world” units in which the quantity of the manufactured item is described */
		public ?FHIRCodeableConcept $unitOfPresentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity The quantity or "count number" of the manufactured item */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturer Manufacturer of the item (Note that this should be named "manufacturer" but it currently causes technical issues) */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> ingredient Ingredient */
		public array $ingredient = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?FHIRProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> otherCharacteristics Other codeable characteristics */
		public array $otherCharacteristics = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
