<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Evidence.statistic.modelCharacteristic
 * @description A component of the method to generate the statistic.
 */
class FHIREvidenceStatisticModelCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Model specification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity value Numerical value to complete model specification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $value = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceStatisticModelCharacteristicVariable> variable A variable adjusted for in the adjusted analysis */
		public array $variable = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceStatisticAttributeEstimate> attributeEstimate An attribute of the statistic used as a model characteristic */
		public array $attributeEstimate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
