<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Schedule information for an enteral formula.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'NutritionOrder',
	elementPath: 'NutritionOrder.enteralFormula.administration.schedule',
	fhirVersion: 'R5',
)]
class FHIRNutritionOrderEnteralFormulaAdministrationSchedule extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming> timing Scheduled frequency of enteral formula */
		public array $timing = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean asNeeded Take 'as needed' */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $asNeeded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept asNeededFor Take 'as needed' for x */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $asNeededFor = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
