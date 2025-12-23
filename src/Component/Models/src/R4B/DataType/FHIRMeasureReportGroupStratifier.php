<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MeasureReport.group.stratifier
 * @description When a measure includes multiple stratifiers, there will be a stratifier group for each stratifier defined by the measure.
 */
class FHIRMeasureReportGroupStratifier extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> code What stratifier of the group */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeasureReportGroupStratifierStratum> stratum Stratum results, one for each unique value, or set of values, in the stratifier, or stratifier components */
		public array $stratum = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
