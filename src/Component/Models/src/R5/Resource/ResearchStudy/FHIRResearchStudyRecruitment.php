<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Target or actual group of participants enrolled in study.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.recruitment', fhirVersion: 'R5')]
class FHIRResearchStudyRecruitment extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt targetNumber Estimated total number of participants to be enrolled */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt $targetNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt actualNumber Actual total number of participants enrolled in study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt $actualNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference eligibility Inclusion and exclusion criteria */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $eligibility = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference actualGroup Group of participants who were enrolled in study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $actualGroup = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
