<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Condition;

/**
 * @description Clinical stage or grade of a condition. May include formal severity assessments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Condition', elementPath: 'Condition.stage', fhirVersion: 'R4')]
class ConditionStage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept summary Simple summary (disease specific) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $summary = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> assessment Formal record of assessment */
		public array $assessment = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Kind of staging */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
