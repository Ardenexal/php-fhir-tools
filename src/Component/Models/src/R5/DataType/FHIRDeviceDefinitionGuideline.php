<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element DeviceDefinition.guideline
 * @description Information aimed at providing directions for the usage of this model of device.
 */
class FHIRDeviceDefinitionGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext The circumstances that form the setting for using the device */
		public array $useContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown usageInstruction Detailed written and visual directions for the user on how to use the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $usageInstruction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifact> relatedArtifact A source of information or reference for this guideline */
		public array $relatedArtifact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> indication A clinical condition for which the device was designed to be used */
		public array $indication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> contraindication A specific situation when a device should not be used because it may cause harm */
		public array $contraindication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> warning Specific hazard alert information that a user needs to know before using the device */
		public array $warning = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string intendedUse A description of the general purpose or medical use of the device or its function */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $intendedUse = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
