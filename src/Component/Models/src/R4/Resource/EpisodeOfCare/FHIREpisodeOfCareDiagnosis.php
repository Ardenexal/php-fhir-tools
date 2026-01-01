<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description The list of diagnosis relevant to this episode of care.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EpisodeOfCare', elementPath: 'EpisodeOfCare.diagnosis', fhirVersion: 'R4')]
class FHIREpisodeOfCareDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference condition Conditions/problems/diagnoses this episode of care is for */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $condition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept role Role that this diagnosis has within the episode of care (e.g. admission, billing, discharge …) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt rank Ranking of the diagnosis (for each role type) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $rank = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
