<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

/**
 * @description The matter of concern in the context of this provision of the agrement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.offer', fhirVersion: 'R4')]
class ContractTermOffer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Offer business ID */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermOfferParty> party Offer Recipient */
		public array $party = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference topic Negotiable offer asset */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $topic = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Contract Offer Type or Form */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept decision Accepting party choice */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $decision = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> decisionMode How decision is conveyed */
		public array $decisionMode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermOfferAnswer> answer Response to offer text */
		public array $answer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Human readable offer text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> linkId Pointer to text */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive> securityLabelNumber Offer restriction numbers */
		public array $securityLabelNumber = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
