<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.definition.resource
 * @description A resource that is part of the implementation guide. Conformance resources (value set, structure definition, capability statements etc.) are obvious candidates for inclusion, but any kind of resource can be included as an example resource.
 */
class FHIRImplementationGuideDefinitionResource extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRVersionType> fhirVersion Versions this applies to (if different to IG) */
		public array $fhirVersion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Human readable name for the resource */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Reason why included in guide */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean isExample Is this an example */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $isExample = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> profile Profile(s) this is an example of */
		public array $profile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId groupingId Grouping this is part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $groupingId = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
