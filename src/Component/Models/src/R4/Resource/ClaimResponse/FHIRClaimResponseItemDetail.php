<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A claim detail. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail', fhirVersion: 'R4')]
class FHIRClaimResponseItemDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt detailSequence Claim detail instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $detailSequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRClaimResponseItemAdjudication> adjudication Detail level adjudication details */
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRClaimResponseItemDetailSubDetail> subDetail Adjudication for claim sub-details */
		public array $subDetail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
