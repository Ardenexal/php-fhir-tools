<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition;

/**
 * @description Characteristics for quantitative results of this observation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.quantitativeDetails', fhirVersion: 'R4')]
class ObservationDefinitionQuantitativeDetails extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept customaryUnit Customary unit for quantitative results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $customaryUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unit SI unit for quantitative results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unit = null,
		/** @var null|float conversionFactor SI to Customary unit conversion factor */
		public ?float $conversionFactor = null,
		/** @var null|int decimalPrecision Decimal precision of observation quantitative results */
		public ?int $decimalPrecision = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
