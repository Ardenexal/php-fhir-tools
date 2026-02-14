<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

/**
 * @description Include one or more codes from a code system or other value set(s).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include', fhirVersion: 'R4')]
class ValueSetComposeInclude extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive system The system the codes come from */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Specific version of the code system referred to */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeIncludeConcept> concept A concept defined in the system */
		public array $concept = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeIncludeFilter> filter Select codes/concepts by their properties (including relationships) */
		public array $filter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> valueSet Select the contents included in this value set */
		public array $valueSet = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
