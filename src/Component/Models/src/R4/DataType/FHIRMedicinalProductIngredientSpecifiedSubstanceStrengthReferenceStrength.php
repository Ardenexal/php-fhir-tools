<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MedicinalProductIngredient.specifiedSubstance.strength.referenceStrength
 * @description Strength expressed in terms of a reference substance.
 */
class FHIRMedicinalProductIngredientSpecifiedSubstanceStrengthReferenceStrength extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept substance Relevant reference substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $substance = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio strength Strength expressed in terms of a reference substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio $strength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio strengthLowLimit Strength expressed in terms of a reference substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio $strengthLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string measurementPoint For when strength is measured at a particular point or distance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $measurementPoint = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> country The country or countries for which the strength range applies */
		public array $country = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
