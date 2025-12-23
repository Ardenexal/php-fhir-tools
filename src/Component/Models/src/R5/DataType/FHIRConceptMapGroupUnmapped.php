<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ConceptMap.group.unmapped
 * @description What to do when there is no mapping to a target concept from the source concept and ConceptMap.group.element.noMap is not true. This provides the "default" to be applied when there is no target concept mapping specified or the expansion of ConceptMap.group.element.target.valueSet is empty.
 */
class FHIRConceptMapGroupUnmapped extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupUnmappedModeType mode use-source-code | fixed | other-map */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupUnmappedModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode code Fixed code when mode = fixed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string display Display for the code */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical valueSet Fixed code set when mode = fixed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $valueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapRelationshipType relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapRelationshipType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical otherMap canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $otherMap = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
