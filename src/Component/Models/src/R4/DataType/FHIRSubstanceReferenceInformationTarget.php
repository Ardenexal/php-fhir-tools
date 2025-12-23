<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstanceReferenceInformation.target
 * @description Todo.
 */
class FHIRSubstanceReferenceInformationTarget extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier target Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept interaction Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $interaction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept organism Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $organism = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept organismType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $organismType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string amountX Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept amountType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $amountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> source Todo */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
