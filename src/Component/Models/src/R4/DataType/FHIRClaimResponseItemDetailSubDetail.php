<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ClaimResponse.item.detail.subDetail
 * @description A sub-detail adjudication of a simple product or service.
 */
class FHIRClaimResponseItemDetailSubDetail extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt subDetailSequence Claim sub-detail instance identifier */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $subDetailSequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt> noteNumber Applicable note numbers */
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRClaimResponseItemAdjudication> adjudication Subdetail level adjudication details */
		public array $adjudication = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
