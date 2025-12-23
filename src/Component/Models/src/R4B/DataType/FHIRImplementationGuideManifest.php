<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.manifest
 * @description Information about an assembled implementation guide, created by the publication tooling.
 */
class FHIRImplementationGuideManifest extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl rendering Location of rendered implementation guide */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl $rendering = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideManifestResource> resource Resource in the implementation guide */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRImplementationGuideManifestPage> page HTML page within the parent IG */
		public array $page = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> image Image within the IG */
		public array $image = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> other Additional linkable file in IG */
		public array $other = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
