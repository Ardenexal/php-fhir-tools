<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

/**
 * @description A resource that is part of the implementation guide. Conformance resources (value set, structure definition, capability statements etc.) are obvious candidates for inclusion, but any kind of resource can be included as an example resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.manifest.resource', fhirVersion: 'R4')]
class ImplementationGuideManifestResource extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference reference Location of the resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reference = null,
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive exampleX Is an example/What is this an example of? */
		public bool|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|null $exampleX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive relativePath Relative path for page in IG */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $relativePath = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
