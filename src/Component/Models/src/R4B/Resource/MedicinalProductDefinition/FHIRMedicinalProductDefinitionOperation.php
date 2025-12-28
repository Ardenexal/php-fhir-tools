<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A manufacturing or administrative process or step associated with (or performed on) the medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.operation', fhirVersion: 'R4B')]
class FHIRMedicinalProductDefinitionOperation extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference type The type of manufacturing operation e.g. manufacturing itself, re-packaging */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod effectiveDate Date range of applicability */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod $effectiveDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> organization The organization responsible for the particular process, e.g. the manufacturer or importer */
		public array $organization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept confidentialityIndicator Specifies whether this process is considered proprietary or confidential */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $confidentialityIndicator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
