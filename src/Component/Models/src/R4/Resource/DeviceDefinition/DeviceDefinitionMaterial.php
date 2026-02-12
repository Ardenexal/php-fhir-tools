<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

/**
 * @description A substance used to create the material(s) of which the device is made.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.material', fhirVersion: 'R4')]
class DeviceDefinitionMaterial extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept substance The substance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $substance = null,
		/** @var null|bool alternate Indicates an alternative material of the device */
		public ?bool $alternate = null,
		/** @var null|bool allergenicIndicator Whether the substance is a known or suspected allergen */
		public ?bool $allergenicIndicator = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
