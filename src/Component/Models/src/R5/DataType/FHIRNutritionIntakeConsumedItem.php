<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element NutritionIntake.consumedItem
 * @description What food or fluid product or item was consumed.
 */
class FHIRNutritionIntakeConsumedItem extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of food or fluid product */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference nutritionProduct Code that identifies the food or fluid product that was consumed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $nutritionProduct = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming schedule Scheduled frequency of consumption */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming $schedule = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity amount Quantity of the specified food */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity rate Rate at which enteral feeding was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $rate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean notConsumed Flag to indicate if the food or fluid item was refused or otherwise not consumed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $notConsumed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept notConsumedReason Reason food or fluid was not consumed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $notConsumedReason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
