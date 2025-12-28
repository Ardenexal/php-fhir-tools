<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Contract Term Asset List.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset', fhirVersion: 'R5')]
class FHIRContractTermAsset extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept scope Range of asset */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $scope = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> type Asset category */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> typeReference Associated entities */
		public array $typeReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> subtype Asset sub-category */
		public array $subtype = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding relationship Kinship of the asset */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $relationship = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermAssetContext> context Circumstance of the asset */
		public array $context = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string condition Quality desctiption of asset */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $condition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> periodType Asset availability types */
		public array $periodType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod> period Time period of the asset */
		public array $period = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod> usePeriod Time period */
		public array $usePeriod = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string text Asset clause or question text */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> linkId Pointer to asset text */
		public array $linkId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermOfferAnswer> answer Response to assets */
		public array $answer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt> securityLabelNumber Asset restriction numbers */
		public array $securityLabelNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContractTermAssetValuedItem> valuedItem Contract Valued Item List */
		public array $valuedItem = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
