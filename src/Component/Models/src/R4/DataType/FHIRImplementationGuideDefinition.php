<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.definition
 * @description The information needed by an IG publisher tool to publish the whole implementation guide.
 */
class FHIRImplementationGuideDefinition extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionGrouping> grouping Grouping used to present related resources in the IG */
		public array $grouping = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionResource> resource Resource in the implementation guide */
		public array $resource = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionPage page Page/Section in the Guide */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionPage $page = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionParameter> parameter Defines how IG is built by tools */
		public array $parameter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionTemplate> template A template for building resources */
		public array $template = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
