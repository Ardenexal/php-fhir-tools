<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.compose.include.concept
 * @description Specifies a concept to be included or excluded.
 */
class FHIRValueSetComposeIncludeConcept extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode code Code or expression from system */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string display Text to display for this code for this value set in this valueset */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $display = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetComposeIncludeConceptDesignation> designation Additional representations for this concept */
		public array $designation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
