<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Device.udiCarrier
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
class FHIRDeviceUdiCarrier extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string deviceIdentifier Mandatory fixed portion of UDI */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $deviceIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri issuer UDI Issuing Organization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $issuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri jurisdiction Regional UDI authority */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $jurisdiction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary carrierAIDC UDI Machine Readable Barcode String */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary $carrierAIDC = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string carrierHRF UDI Human Readable Barcode String */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $carrierHRF = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUDIEntryTypeType entryType barcode | rfid | manual + */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUDIEntryTypeType $entryType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
