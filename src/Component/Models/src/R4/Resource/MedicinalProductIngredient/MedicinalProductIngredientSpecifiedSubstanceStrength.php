<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

/**
 * @description Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductIngredient',
	elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength',
	fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstanceStrength extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio presentation The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $presentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio presentationLowLimit A lower limit for the quantity of substance in the unit of presentation. For use when there is a range of strengths, this is the lower limit, with the presentation attribute becoming the upper limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $presentationLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio concentration The strength per unitary volume (or mass) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $concentration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio concentrationLowLimit A lower limit for the strength per unitary volume (or mass), for when there is a range. The concentration attribute then becomes the upper limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $concentrationLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string measurementPoint For when strength is measured at a particular point or distance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> country The country or countries for which the strength range applies */
		public array $country = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient\MedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
		public array $referenceStrength = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
