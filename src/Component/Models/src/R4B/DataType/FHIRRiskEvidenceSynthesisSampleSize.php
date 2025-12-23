<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element RiskEvidenceSynthesis.sampleSize
 * @description A description of the size of the sample involved in the synthesis.
 */
class FHIRRiskEvidenceSynthesisSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Description of sample size */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger numberOfStudies How many studies? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $numberOfStudies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger numberOfParticipants How many participants? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $numberOfParticipants = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
