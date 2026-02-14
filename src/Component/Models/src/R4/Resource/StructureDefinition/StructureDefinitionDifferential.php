<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition;

/**
 * @description A differential view is expressed relative to the base StructureDefinition - a statement of differences that it applies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.differential', fhirVersion: 'R4')]
class StructureDefinitionDifferential extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
		public array $element = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
