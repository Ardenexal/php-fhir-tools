<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis;

/**
 * @description The estimated effect of the exposure variant.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.effectEstimate', fhirVersion: 'R4')]
class EffectEvidenceSynthesisEffectEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of effect estimate */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of efffect estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept variantState Variant exposure states */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $variantState = null,
		/** @var null|float value Point estimate */
		public ?float $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $unitOfMeasure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis\EffectEvidenceSynthesisEffectEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
		public array $precisionEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
