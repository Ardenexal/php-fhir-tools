<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Device.udiCarrier
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
class FHIRDeviceUdiCarrier extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string deviceIdentifier Mandatory fixed portion of UDI */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $deviceIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri issuer UDI Issuing Organization */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $issuer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri jurisdiction Regional UDI authority */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $jurisdiction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBase64Binary carrierAIDC UDI Machine Readable Barcode String */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBase64Binary $carrierAIDC = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string carrierHRF UDI Human Readable Barcode String */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $carrierHRF = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUDIEntryTypeType entryType barcode | rfid | manual | card | self-reported | electronic-transmission | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUDIEntryTypeType $entryType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
