<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The quantity of substance in the unit of presentation, or in the volume (or mass) of the single pharmaceutical product or manufactured item. The allowed repetitions do not represent different strengths, but are different representations - mathematically equivalent - of a single strength.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.substance.strength', fhirVersion: 'R4B')]
class FHIRIngredientSubstanceStrength extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange presentationX The quantity of substance in the unit of presentation */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange|null $presentationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string textPresentation Text of either the whole presentation strength or a part of it (rest being in Strength.presentation as a ratio) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $textPresentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange concentrationX The strength per unitary volume (or mass) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange|null $concentrationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string textConcentration Text of either the whole concentration strength or a part of it (rest being in Strength.concentration as a ratio) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $textConcentration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string measurementPoint When strength is measured at a particular point or distance */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> country Where the strength range applies */
		public array $country = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIngredientSubstanceStrengthReferenceStrength> referenceStrength Strength expressed in terms of a reference substance */
		public array $referenceStrength = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
