<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ConceptMap.group
 * @description A group of mappings that all have the same source and target system.
 */
class FHIRConceptMapGroup extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical source Source system where concepts to be mapped are defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical target Target system that the concepts are to be mapped to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $target = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupElement> element Mappings for a concept from the source set */
		public array $element = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupUnmapped unmapped What to do when there is no mapping target for the source concept and ConceptMap.group.element.noMap is not true */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupUnmapped $unmapped = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
