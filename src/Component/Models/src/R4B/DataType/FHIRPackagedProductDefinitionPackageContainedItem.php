<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PackagedProductDefinition.package.containedItem
 * @description The item(s) within the packaging.
 */
class FHIRPackagedProductDefinitionPackageContainedItem extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference item The actual item(s) of medication, as manufactured, or a device, or other medically related item (food, biologicals, raw materials, medical fluids, gases etc.), as contained in the package */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $item = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity amount The number of this type of item within this packaging */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
