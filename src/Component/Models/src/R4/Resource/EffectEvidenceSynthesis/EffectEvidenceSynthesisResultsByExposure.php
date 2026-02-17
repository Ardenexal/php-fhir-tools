<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis;

/**
 * @description A description of the results for each exposure considered in the effect estimate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.resultsByExposure', fhirVersion: 'R4')]
class EffectEvidenceSynthesisResultsByExposure extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of results by exposure */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ExposureStateType exposureState exposure | exposure-alternative */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ExposureStateType $exposureState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept variantState Variant exposure states */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $variantState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference riskEvidenceSynthesis Risk evidence synthesis */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $riskEvidenceSynthesis = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
