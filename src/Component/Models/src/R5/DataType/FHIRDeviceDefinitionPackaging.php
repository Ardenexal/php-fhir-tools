<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element DeviceDefinition.packaging
 * @description Information about the packaging of the device, i.e. how the device is packaged.
 */
class FHIRDeviceDefinitionPackaging extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Business identifier of the packaged medication */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type A code that defines the specific type of packaging */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger count The number of items contained in the package (devices or sub-packages) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $count = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionPackagingDistributor> distributor An organization that distributes the packaged device */
		public array $distributor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string on the packaging */
		public array $udiDeviceIdentifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionPackaging> packaging Allows packages within packages */
		public array $packaging = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
