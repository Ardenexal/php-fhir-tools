<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Ingredient.substance.strength.referenceStrength
 * @description Strength expressed in terms of a reference substance. For when the ingredient strength is additionally expressed as equivalent to the strength of some other closely related substance (e.g. salt vs. base). Reference strength represents the strength (quantitative composition) of the active moiety of the active substance. There are situations when the active substance and active moiety are different, therefore both a strength and a reference strength are needed.
 */
class FHIRIngredientSubstanceStrengthReferenceStrength extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference substance Relevant reference substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $substance = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatioRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity strengthX Strength expressed in terms of a reference substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRatioRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|null $strengthX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string measurementPoint When strength is measured at a particular point or distance */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> country Where the strength range applies */
		public array $country = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
