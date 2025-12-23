<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ExplanationOfBenefit.accident
 * @description Details of a accident which resulted in injuries which required the products and services listed in the claim.
 */
class FHIRExplanationOfBenefitAccident extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate date When the incident occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The nature of the accident */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference locationX Where the event occurred */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $locationX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
