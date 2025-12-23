<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ResearchStudy.recruitment
 * @description Target or actual group of participants enrolled in study.
 */
class FHIRResearchStudyRecruitment extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt targetNumber Estimated total number of participants to be enrolled */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $targetNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt actualNumber Actual total number of participants enrolled in study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $actualNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference eligibility Inclusion and exclusion criteria */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $eligibility = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference actualGroup Group of participants who were enrolled in study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $actualGroup = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
