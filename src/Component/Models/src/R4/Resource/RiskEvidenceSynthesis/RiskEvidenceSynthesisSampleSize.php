<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

/**
 * @description A description of the size of the sample involved in the synthesis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RiskEvidenceSynthesis', elementPath: 'RiskEvidenceSynthesis.sampleSize', fhirVersion: 'R4')]
class RiskEvidenceSynthesisSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of sample size */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|int numberOfStudies How many studies? */
		public ?int $numberOfStudies = null,
		/** @var null|int numberOfParticipants How many participants? */
		public ?int $numberOfParticipants = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
