<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-complex-type ElementDefinition.binding
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
class FHIRElementDefinitionBinding extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBindingStrengthType strength required | extensible | preferred | example */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBindingStrengthType $strength = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Human explanation of the value set */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical valueSet Source of value set */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $valueSet = null,
	) {
		parent::__construct($id, $extension);
	}
}
