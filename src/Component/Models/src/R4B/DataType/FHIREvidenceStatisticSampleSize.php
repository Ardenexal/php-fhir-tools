<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Evidence.statistic.sampleSize
 * @description Number of samples in the statistic.
 */
class FHIREvidenceStatisticSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Textual description of sample size for statistic */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Footnote or explanatory note about the sample size */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt numberOfStudies Number of contributing studies */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt $numberOfStudies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt numberOfParticipants Cumulative number of participants */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt $numberOfParticipants = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt knownDataCount Number of participants with known results for measured variables */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt $knownDataCount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
