<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ClaimResponse.error
 * @description Errors encountered during the processing of the adjudication.
 */
class FHIRClaimResponseError extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt itemSequence Item sequence number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $itemSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt detailSequence Detail sequence number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $detailSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt subDetailSequence Subdetail sequence number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt $subDetailSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code Error code detailing processing issues */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $code = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
