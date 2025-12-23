<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ValueSet.compose.include.concept.designation
 * @description Additional representations for this concept when used in this value set - other languages, aliases, specialized purposes, used for particular purposes, etc.
 */
class FHIRValueSetComposeIncludeConceptDesignation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|string language Human language of the designation */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding use Types of uses of designations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string value The text value for this designation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
