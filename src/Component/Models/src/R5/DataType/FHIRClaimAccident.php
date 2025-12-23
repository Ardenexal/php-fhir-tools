<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Claim.accident
 * @description Details of an accident which resulted in injuries which required the products and services listed in the claim.
 */
class FHIRClaimAccident extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate date When the incident occurred */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The nature of the accident */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference locationX Where the event occurred */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $locationX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
