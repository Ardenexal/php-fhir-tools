<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element EffectEvidenceSynthesis.sampleSize
 * @description A description of the size of the sample involved in the synthesis.
 */
class FHIREffectEvidenceSynthesisSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Description of sample size */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger numberOfStudies How many studies? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $numberOfStudies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger numberOfParticipants How many participants? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $numberOfParticipants = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
