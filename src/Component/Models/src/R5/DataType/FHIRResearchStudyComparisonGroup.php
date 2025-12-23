<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ResearchStudy.comparisonGroup
 * @description Describes an expected event or sequence of events for one of the subjects of a study. E.g. for a living subject: exposure to drug A, wash-out, exposure to drug B, wash-out, follow-up. E.g. for a stability study: {store sample from lot A at 25 degrees for 1 month}, {store sample from lot A at 40 degrees for 1 month}.
 */
class FHIRResearchStudyComparisonGroup extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId linkId Allows the comparisonGroup for the study and the comparisonGroup for the subject to be linked easily */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Label for study comparisonGroup */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Categorization of study comparisonGroup */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Short explanation of study path */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> intendedExposure Interventions or exposures in this comparisonGroup or cohort */
		public array $intendedExposure = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference observedGroup Group of participants who were enrolled in study comparisonGroup */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $observedGroup = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
