<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicinalProduct.specialDesignation
 * @description Indicates if the medicinal product has an orphan designation for the treatment of a rare disease.
 */
class FHIRMedicinalProductSpecialDesignation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Identifier for the designation, or procedure number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type The type of special designation, e.g. orphan drug, minor use */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept intendedUse The intended use of the product, e.g. prevention, treatment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $intendedUse = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference indicationX Condition for which the medicinal use applies */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $indicationX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept status For example granted, pending, expired or withdrawn */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime date Date when the designation was granted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept species Animal species for which this applies */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $species = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
