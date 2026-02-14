<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

/**
 * @description Strength expressed in terms of a reference substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductIngredient',
	elementPath: 'MedicinalProductIngredient.specifiedSubstance.strength.referenceStrength',
	fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept substance Relevant reference substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $substance = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio strength Strength expressed in terms of a reference substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $strength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio strengthLowLimit Strength expressed in terms of a reference substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $strengthLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string measurementPoint For when strength is measured at a particular point or distance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> country The country or countries for which the strength range applies */
		public array $country = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
