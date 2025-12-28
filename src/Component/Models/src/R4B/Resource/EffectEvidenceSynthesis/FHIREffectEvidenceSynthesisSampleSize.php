<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A description of the size of the sample involved in the synthesis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.sampleSize', fhirVersion: 'R4B')]
class FHIREffectEvidenceSynthesisSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Description of sample size */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger numberOfStudies How many studies? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $numberOfStudies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger numberOfParticipants How many participants? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $numberOfParticipants = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
