<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element DetectedIssue.mitigation
 * @description Indicates an action that has been taken or is committed to reduce or eliminate the likelihood of the risk identified by the detected issue from manifesting.  Can also reflect an observation of known mitigating factors that may reduce/eliminate the need for any action.
 */
class FHIRDetectedIssueMitigation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept action What mitigation? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $action = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime date Date committed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference author Who is committing? */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $author = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Additional notes about the mitigation */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
