<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.manifest.resource
 * @description A resource that is part of the implementation guide. Conformance resources (value set, structure definition, capability statements etc.) are obvious candidates for inclusion, but any kind of resource can be included as an example resource.
 */
class FHIRImplementationGuideManifestResource extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Location of the resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean isExample Is this an example */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $isExample = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> profile Profile(s) this is an example of */
		public array $profile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl relativePath Relative path for page in IG */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl $relativePath = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
