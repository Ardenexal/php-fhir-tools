<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet', fhirVersion: 'R4')]
class SubstancePolymerMonomerSet extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept ratioType Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $ratioType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerMonomerSetStartingMaterial> startingMaterial Todo */
		public array $startingMaterial = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
