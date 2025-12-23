<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Device.version
 * @description The actual design of the device or software version running on the device.
 */
class FHIRDeviceVersion extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type The type of the device version, e.g. manufacturer, approved, internal */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier component The hardware or software module of the device to which the version applies */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier $component = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime installDate The date the version was installed on the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $installDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string value The version text */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
