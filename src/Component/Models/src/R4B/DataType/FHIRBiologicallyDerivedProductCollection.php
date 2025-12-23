<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element BiologicallyDerivedProduct.collection
 * @description How this product was collected.
 */
class FHIRBiologicallyDerivedProductCollection extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference collector Individual performing collection */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $collector = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference source Who is product from */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod collectedX Time of product collection */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|null $collectedX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
