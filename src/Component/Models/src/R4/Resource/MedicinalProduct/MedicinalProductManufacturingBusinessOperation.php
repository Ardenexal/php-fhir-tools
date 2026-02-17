<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

/**
 * @description An operation applied to the product, for manufacturing or adminsitrative purpose.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.manufacturingBusinessOperation', fhirVersion: 'R4')]
class MedicinalProductManufacturingBusinessOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept operationType The type of manufacturing operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $operationType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier authorisationReferenceNumber Regulatory authorization reference number */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $authorisationReferenceNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive effectiveDate Regulatory authorization date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $effectiveDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept confidentialityIndicator To indicate if this proces is commercially confidential */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $confidentialityIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> manufacturer The manufacturer or establishment associated with the process */
		public array $manufacturer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference regulator A regulator which oversees the operation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $regulator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
