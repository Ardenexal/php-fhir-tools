<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicinalProductDefinition.operation
 * @description A manufacturing or administrative process or step associated with (or performed on) the medicinal product.
 */
class FHIRMedicinalProductDefinitionOperation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference type The type of manufacturing operation e.g. manufacturing itself, re-packaging */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod effectiveDate Date range of applicability */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $effectiveDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> organization The organization responsible for the particular process, e.g. the manufacturer or importer */
		public array $organization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept confidentialityIndicator Specifies whether this process is considered proprietary or confidential */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $confidentialityIndicator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
