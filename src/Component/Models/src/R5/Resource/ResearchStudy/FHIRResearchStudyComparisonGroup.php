<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Describes an expected event or sequence of events for one of the subjects of a study. E.g. for a living subject: exposure to drug A, wash-out, exposure to drug B, wash-out, follow-up. E.g. for a stability study: {store sample from lot A at 25 degrees for 1 month}, {store sample from lot A at 40 degrees for 1 month}.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.comparisonGroup', fhirVersion: 'R5')]
class FHIRResearchStudyComparisonGroup extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId linkId Allows the comparisonGroup for the study and the comparisonGroup for the subject to be linked easily */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Label for study comparisonGroup */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Categorization of study comparisonGroup */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Short explanation of study path */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> intendedExposure Interventions or exposures in this comparisonGroup or cohort */
		public array $intendedExposure = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference observedGroup Group of participants who were enrolled in study comparisonGroup */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $observedGroup = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
