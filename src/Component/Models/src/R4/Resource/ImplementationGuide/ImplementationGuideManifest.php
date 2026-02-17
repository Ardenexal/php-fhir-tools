<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

/**
 * @description Information about an assembled implementation guide, created by the publication tooling.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.manifest', fhirVersion: 'R4')]
class ImplementationGuideManifest extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive rendering Location of rendered implementation guide */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $rendering = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideManifestResource> resource Resource in the implementation guide */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideManifestPage> page HTML page within the parent IG */
		public array $page = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> image Image within the IG */
		public array $image = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> other Additional linkable file in IG */
		public array $other = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
