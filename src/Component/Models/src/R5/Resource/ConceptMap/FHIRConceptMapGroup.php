<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A group of mappings that all have the same source and target system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group', fhirVersion: 'R5')]
class FHIRConceptMapGroup extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical source Source system where concepts to be mapped are defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical target Target system that the concepts are to be mapped to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $target = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupElement> element Mappings for a concept from the source set */
		public array $element = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupUnmapped unmapped What to do when there is no mapping target for the source concept and ConceptMap.group.element.noMap is not true */
		public ?FHIRConceptMapGroupUnmapped $unmapped = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
