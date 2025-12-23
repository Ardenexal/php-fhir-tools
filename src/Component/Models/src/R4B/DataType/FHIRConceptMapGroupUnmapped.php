<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ConceptMap.group.unmapped
 * @description What to do when there is no mapping for the source concept. "Unmapped" does not include codes that are unmatched, and the unmapped element is ignored in a code is specified to have equivalence = unmatched.
 */
class FHIRConceptMapGroupUnmapped extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConceptMapGroupUnmappedModeType mode provided | fixed | other-map */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRConceptMapGroupUnmappedModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode code Fixed code when mode = fixed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string display Display for the code */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical url canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $url = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
