<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

/**
 * @description The information needed by an IG publisher tool to publish the whole implementation guide.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition', fhirVersion: 'R4')]
class ImplementationGuideDefinition extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionGrouping> grouping Grouping used to present related resources in the IG */
		public array $grouping = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionResource> resource Resource in the implementation guide */
		public array $resource = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionPage page Page/Section in the Guide */
		public ?ImplementationGuideDefinitionPage $page = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionParameter> parameter Defines how IG is built by tools */
		public array $parameter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionTemplate> template A template for building resources */
		public array $template = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
