<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element DeviceDefinition.hasPart
 * @description A device that is part (for example a component) of the present device.
 */
class FHIRDeviceDefinitionHasPart extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Reference to the part */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger count Number of occurrences of the part */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $count = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
