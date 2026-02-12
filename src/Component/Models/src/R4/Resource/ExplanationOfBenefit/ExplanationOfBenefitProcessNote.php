<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description A note that describes or explains adjudication results in a human readable form.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.processNote', fhirVersion: 'R4')]
class ExplanationOfBenefitProcessNote extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive number Note instance identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\NoteTypeType type display | print | printoper */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\NoteTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Note explanatory text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept language Language of the text */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $language = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
