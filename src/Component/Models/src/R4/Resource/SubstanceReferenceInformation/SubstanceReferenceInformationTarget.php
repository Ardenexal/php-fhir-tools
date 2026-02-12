<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceReferenceInformation', elementPath: 'SubstanceReferenceInformation.target', fhirVersion: 'R4')]
class SubstanceReferenceInformationTarget extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier target Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept interaction Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $interaction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept organism Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $organism = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept organismType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $organismType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string amountX Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept amountType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $amountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> source Todo */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
