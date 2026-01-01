<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description The estimated effect of the exposure variant.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.effectEstimate', fhirVersion: 'R4')]
class FHIREffectEvidenceSynthesisEffectEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Description of effect estimate */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Type of efffect estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept variantState Variant exposure states */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $variantState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal value Point estimate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept unitOfMeasure What unit is the outcome described in? */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $unitOfMeasure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisEffectEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
		public array $precisionEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
