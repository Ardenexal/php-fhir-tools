<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.expansion.contains
 * @description The codes that are contained in the value set expansion.
 */
class FHIRValueSetExpansionContains extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri system System value for the code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean abstract If user cannot select this entry */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $abstract = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean inactive If concept is inactive in the code system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $inactive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Version in which this code/display is defined */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode code Code - if blank, this is not a selectable code */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string display User display for the concept */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $display = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeIncludeConceptDesignation> designation Additional representations for this item */
		public array $designation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetExpansionContainsProperty> property Property value for the concept */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetExpansionContains> contains Codes contained under this entry */
		public array $contains = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
