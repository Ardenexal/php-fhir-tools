<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Device;

/**
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.udiCarrier', fhirVersion: 'R4')]
class DeviceUdiCarrier extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string deviceIdentifier Mandatory fixed portion of UDI */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $deviceIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive issuer UDI Issuing Organization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $issuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive jurisdiction Regional UDI authority */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $jurisdiction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive carrierAIDC UDI Machine Readable Barcode String */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive $carrierAIDC = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string carrierHRF UDI Human Readable Barcode String */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $carrierHRF = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\UDIEntryTypeType entryType barcode | rfid | manual + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\UDIEntryTypeType $entryType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
