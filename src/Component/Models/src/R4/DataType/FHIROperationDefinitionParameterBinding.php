<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element OperationDefinition.parameter.binding
 * @description Binds to a value set if this parameter is coded (code, Coding, CodeableConcept).
 */
class FHIROperationDefinitionParameterBinding extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBindingStrengthType strength required | extensible | preferred | example */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBindingStrengthType $strength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical valueSet Source of value set */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical $valueSet = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
