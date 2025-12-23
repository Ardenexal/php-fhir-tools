<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element DeviceDefinition.material
 * @description A substance used to create the material(s) of which the device is made.
 */
class FHIRDeviceDefinitionMaterial extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept substance The substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $substance = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean alternate Indicates an alternative material of the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $alternate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean allergenicIndicator Whether the substance is a known or suspected allergen */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $allergenicIndicator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
