<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.compose.include
 * @description Include one or more codes from a code system or other value set(s).
 */
class FHIRValueSetComposeInclude extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri system The system the codes come from */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Specific version of the code system referred to */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $version = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeIncludeConcept> concept A concept defined in the system */
		public array $concept = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeIncludeFilter> filter Select codes/concepts by their properties (including relationships) */
		public array $filter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> valueSet Select the contents included in this value set */
		public array $valueSet = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyright A copyright statement for the specific code system included in the value set */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $copyright = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
