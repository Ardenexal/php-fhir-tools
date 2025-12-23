<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-complex-type ElementDefinition.slicing
 * @description Indicates that the element is sliced into a set of alternative definitions (i.e. in a structure definition, there are multiple different constraints on a single element in the base resource). Slicing can be used in any resource that has cardinality ..* on the base resource, or any resource with a choice of types. The set of slices is any elements that come after this in the element sequence that have the same path, until a shorter path occurs (the shorter path terminates the set).
 */
class FHIRElementDefinitionSlicing extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElementDefinitionSlicingDiscriminator> discriminator Element values that are used to distinguish the slices */
		public array $discriminator = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Text description of how slicing works (or not) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean ordered If elements must be in same order as slices */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $ordered = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSlicingRulesType rules closed | open | openAtEnd */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSlicingRulesType $rules = null,
	) {
		parent::__construct($id, $extension);
	}
}
