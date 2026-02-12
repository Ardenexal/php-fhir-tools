<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient;

/**
 * @description A specified substance that comprises this ingredient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductIngredient',
	elementPath: 'MedicinalProductIngredient.specifiedSubstance',
	fhirVersion: 'R4',
)]
class MedicinalProductIngredientSpecifiedSubstance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code The specified substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept group The group of specified substance, e.g. group 1 to 4 */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $group = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept confidentiality Confidentiality level of the specified substance as the ingredient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $confidentiality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient\MedicinalProductIngredientSpecifiedSubstanceStrength> strength Quantity of the substance or specified substance present in the manufactured item or pharmaceutical product */
		public array $strength = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
