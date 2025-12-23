<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicinalProduct.manufacturingBusinessOperation
 * @description An operation applied to the product, for manufacturing or adminsitrative purpose.
 */
class FHIRMedicinalProductManufacturingBusinessOperation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept operationType The type of manufacturing operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $operationType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier authorisationReferenceNumber Regulatory authorization reference number */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $authorisationReferenceNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime effectiveDate Regulatory authorization date */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $effectiveDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept confidentialityIndicator To indicate if this proces is commercially confidential */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $confidentialityIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> manufacturer The manufacturer or establishment associated with the process */
		public array $manufacturer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference regulator A regulator which oversees the operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $regulator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
