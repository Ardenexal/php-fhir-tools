<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ObservationDefinition.quantitativeDetails
 * @description Characteristics for quantitative results of this observation.
 */
class FHIRObservationDefinitionQuantitativeDetails extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept customaryUnit Customary unit for quantitative results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $customaryUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept unit SI unit for quantitative results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal conversionFactor SI to Customary unit conversion factor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $conversionFactor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger decimalPrecision Decimal precision of observation quantitative results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $decimalPrecision = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
