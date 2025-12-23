<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Contract.term.offer.answer
 * @description Response to offer text.
 */
class FHIRContractTermOfferAnswer extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference valueX The actual answer response */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
