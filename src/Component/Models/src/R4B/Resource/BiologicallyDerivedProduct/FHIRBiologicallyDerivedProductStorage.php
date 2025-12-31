<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Product storage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.storage', fhirVersion: 'R4B')]
class FHIRBiologicallyDerivedProductStorage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Description of storage */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal temperature Storage temperature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $temperature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBiologicallyDerivedProductStorageScaleType scale farenheit | celsius | kelvin */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBiologicallyDerivedProductStorageScaleType $scale = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod duration Storage timeperiod */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod $duration = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
