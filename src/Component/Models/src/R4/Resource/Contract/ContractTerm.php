<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

/**
 * @description One or more Contract Provisions, which may be related and conveyed as a group, and may contain nested groups.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term', fhirVersion: 'R4')]
class ContractTerm extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Contract Term Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive issued Contract Term Issue Date Time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period applies Contract Term Effective Time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $applies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference topicX Term Concern */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $topicX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Contract Term Type or Form */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept subType Contract Term Type specific classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $subType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Term Statement */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermSecurityLabel> securityLabel Protection for the Term */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermOffer offer Context of the Contract term */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?ContractTermOffer $offer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermAsset> asset Contract Term Asset List */
		public array $asset = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermAction> action Entity being ascribed responsibility */
		public array $action = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTerm> group Nested Contract Term Group */
		public array $group = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
