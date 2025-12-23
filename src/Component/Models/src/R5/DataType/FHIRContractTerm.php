<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Contract.term
 * @description One or more Contract Provisions, which may be related and conveyed as a group, and may contain nested groups.
 */
class FHIRContractTerm extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Contract Term Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime issued Contract Term Issue Date Time */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod applies Contract Term Effective Time */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $applies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference topicX Term Concern */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $topicX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Contract Term Type or Form */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept subType Contract Term Type specific classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string text Term Statement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermSecurityLabel> securityLabel Protection for the Term */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermOffer offer Context of the Contract term */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermOffer $offer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermAsset> asset Contract Term Asset List */
		public array $asset = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermAction> action Entity being ascribed responsibility */
		public array $action = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTerm> group Nested Contract Term Group */
		public array $group = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
