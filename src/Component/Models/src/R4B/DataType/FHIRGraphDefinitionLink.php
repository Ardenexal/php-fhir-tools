<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element GraphDefinition.link
 * @description Links this graph makes rules about.
 */
class FHIRGraphDefinitionLink extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string path Path in the resource that contains the link */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string sliceName Which slice (if profiled) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $sliceName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger min Minimum occurrences for this link */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string max Maximum occurrences for this link */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Why this link is specified */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGraphDefinitionLinkTarget> target Potential target for the link */
		public array $target = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
