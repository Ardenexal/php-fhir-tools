<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

/**
 * @description Contract Term Asset List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset', fhirVersion: 'R4')]
class ContractTermAsset extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept scope Range of asset */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $scope = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Asset category */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> typeReference Associated entities */
		public array $typeReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subtype Asset sub-category */
		public array $subtype = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding relationship Kinship of the asset */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $relationship = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermAssetContext> context Circumstance of the asset */
		public array $context = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string condition Quality desctiption of asset */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $condition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> periodType Asset availability types */
		public array $periodType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period> period Time period of the asset */
		public array $period = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period> usePeriod Time period */
		public array $usePeriod = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Asset clause or question text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> linkId Pointer to asset text */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermOfferAnswer> answer Response to assets */
		public array $answer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive> securityLabelNumber Asset restriction numbers */
		public array $securityLabelNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTermAssetValuedItem> valuedItem Contract Valued Item List */
		public array $valuedItem = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
