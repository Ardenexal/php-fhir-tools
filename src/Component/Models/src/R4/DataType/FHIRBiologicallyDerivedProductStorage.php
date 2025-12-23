<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element BiologicallyDerivedProduct.storage
 * @description Product storage.
 */
class FHIRBiologicallyDerivedProductStorage extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Description of storage */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal temperature Storage temperature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $temperature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductStorageScaleType scale farenheit | celsius | kelvin */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductStorageScaleType $scale = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod duration Storage timeperiod */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod $duration = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
