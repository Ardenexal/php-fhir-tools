<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Measure.group
 * @description A group of population criteria for the measure.
 */
class FHIRMeasureGroup extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string linkId Unique id for group in measure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Meaning of the group */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Summary description */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type process | outcome | structure | patient-reported-outcome | composite */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $subjectX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRTypesType basis Population basis */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRTypesType $basis = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept scoring proportion | ratio | continuous-variable | cohort */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $scoring = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept scoringUnit What units? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $scoringUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown rateAggregation How is rate aggregation performed for this measure */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $rateAggregation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept improvementNotation increase | decrease */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $improvementNotation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> library Logic used by the measure group */
		public array $library = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeasureGroupPopulation> population Population criteria */
		public array $population = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeasureGroupStratifier> stratifier Stratifier criteria for the measure */
		public array $stratifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
