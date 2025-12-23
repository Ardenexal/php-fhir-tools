<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.compose
 * @description A set of criteria that define the contents of the value set by including or excluding codes selected from the specified code system(s) that the value set draws from. This is also known as the Content Logical Definition (CLD).
 */
class FHIRValueSetCompose extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate lockedDate Fixed date for references with no specified version (transitive) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $lockedDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean inactive Whether inactive codes are in the value set */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $inactive = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeInclude> include Include one or more codes from a code system or other value set(s) */
		public array $include = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeInclude> exclude Explicitly exclude codes from a code system or other value sets */
		public array $exclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> property Property to return if client doesn't override */
		public array $property = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
