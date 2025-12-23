<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.processNote
 * @description A note that describes or explains adjudication results in a human readable form.
 */
class FHIRExplanationOfBenefitProcessNote extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt number Note instance identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNoteTypeType type display | print | printoper */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNoteTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string text Note explanatory text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept language Language of the text */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $language = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
