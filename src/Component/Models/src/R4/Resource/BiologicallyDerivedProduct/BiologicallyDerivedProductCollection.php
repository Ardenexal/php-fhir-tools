<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct;

/**
 * @description How this product was collected.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.collection', fhirVersion: 'R4')]
class BiologicallyDerivedProductCollection extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference collector Individual performing collection */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $collector = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference source Who is product from */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period collectedX Time of product collection */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $collectedX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
