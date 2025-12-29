<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Substance may be used to describe a kind of substance, or a specific package/container of the substance: an instance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.instance', fhirVersion: 'R4B')]
class FHIRSubstanceInstance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier identifier Identifier of the package/container */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime expiry When no longer valid to use */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $expiry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity quantity Amount of substance in the package */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $quantity = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
