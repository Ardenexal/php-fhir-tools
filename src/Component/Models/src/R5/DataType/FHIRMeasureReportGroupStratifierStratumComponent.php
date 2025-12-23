<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MeasureReport.group.stratifier.stratum.component
 * @description A stratifier component value.
 */
class FHIRMeasureReportGroupStratifierStratumComponent extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Pointer to specific stratifier component from Measure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code What stratifier component of the group */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference valueX The stratum component value, e.g. male */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
