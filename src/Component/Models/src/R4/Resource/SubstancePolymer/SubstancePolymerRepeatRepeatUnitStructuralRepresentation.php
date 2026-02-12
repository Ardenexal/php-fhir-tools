<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstancePolymer',
	elementPath: 'SubstancePolymer.repeat.repeatUnit.structuralRepresentation',
	fhirVersion: 'R4',
)]
class SubstancePolymerRepeatRepeatUnitStructuralRepresentation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string representation Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $representation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment attachment Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment $attachment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
