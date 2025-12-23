<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.definition
 * @description The information needed by an IG publisher tool to publish the whole implementation guide.
 */
class FHIRImplementationGuideDefinition extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionGrouping> grouping Grouping used to present related resources in the IG */
		public array $grouping = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionResource> resource Resource in the implementation guide */
		public array $resource = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionPage page Page/Section in the Guide */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionPage $page = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionParameter> parameter Defines how IG is built by tools */
		public array $parameter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideDefinitionTemplate> template A template for building resources */
		public array $template = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
