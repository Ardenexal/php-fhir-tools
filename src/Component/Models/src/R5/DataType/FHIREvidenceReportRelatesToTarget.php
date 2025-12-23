<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element EvidenceReport.relatesTo.target
 * @description The target composition/document of this relationship.
 */
class FHIREvidenceReportRelatesToTarget extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Target of the relationship URL */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Target of the relationship Identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown display Target of the relationship Display */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference resource Target of the relationship Resource reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $resource = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
