<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description What food or fluid product or item was consumed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionIntake', elementPath: 'NutritionIntake.consumedItem', fhirVersion: 'R5')]
class FHIRNutritionIntakeConsumedItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type The type of food or fluid product */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference nutritionProduct Code that identifies the food or fluid product that was consumed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $nutritionProduct = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming schedule Scheduled frequency of consumption */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming $schedule = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity amount Quantity of the specified food */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $amount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity rate Rate at which enteral feeding was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $rate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean notConsumed Flag to indicate if the food or fluid item was refused or otherwise not consumed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $notConsumed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept notConsumedReason Reason food or fluid was not consumed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $notConsumedReason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
