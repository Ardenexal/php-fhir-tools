<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element EvidenceReport.section
 * @description The root of the sections that make up the composition.
 */
class FHIREvidenceReportSection extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string title Label for section (e.g. for ToC) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept focus Classification of section (recommended) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $focus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference focusReference Classification of section by Resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $focusReference = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> author Who and/or what authored the section */
		public array $author = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the section, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRListModeType mode working | snapshot | changes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRListModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept orderedBy Order of section entries */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $orderedBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> entryClassifier Extensible classifiers as content */
		public array $entryClassifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> entryReference Reference to resources as content */
		public array $entryReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity> entryQuantity Quantity as content */
		public array $entryQuantity = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept emptyReason Why the section is empty */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $emptyReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceReportSection> section Nested Section */
		public array $section = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
