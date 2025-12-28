<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A group of mappings that all have the same source and target system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group', fhirVersion: 'R4')]
class FHIRConceptMapGroup extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri source Source system where concepts to be mapped are defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string sourceVersion Specific version of the  code system */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $sourceVersion = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri target Target system that the concepts are to be mapped to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string targetVersion Specific version of the  code system */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $targetVersion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapGroupElement> element Mappings for a concept from the source set */
		public array $element = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapGroupUnmapped unmapped What to do when there is no mapping for the source concept */
		public ?FHIRConceptMapGroupUnmapped $unmapped = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
