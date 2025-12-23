<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PaymentReconciliation.processNote
 * @description A note that describes or explains the processing in a human readable form.
 */
class FHIRPaymentReconciliationProcessNote extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNoteTypeType type display | print | printoper */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNoteTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string text Note explanatory text */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
