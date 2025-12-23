<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element EvidenceReport.relatesTo
 * @description Relationships that this composition has with other compositions or documents that already exist.
 */
class FHIREvidenceReportRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReportRelationshipTypeType code replaces | amends | appends | transforms | replacedWith | amendedWith | appendedWith | transformedWith */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReportRelationshipTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceReportRelatesToTarget target Target of the relationship */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceReportRelatesToTarget $target = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
