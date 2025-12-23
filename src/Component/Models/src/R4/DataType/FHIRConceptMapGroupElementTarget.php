<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ConceptMap.group.element.target
 * @description A concept from the target value set that this concept maps to.
 */
class FHIRConceptMapGroupElementTarget extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode code Code that identifies the target element */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string display Display for the code */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapEquivalenceType equivalence relatedto | equivalent | equal | wider | subsumes | narrower | specializes | inexact | unmatched | disjoint */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapEquivalenceType $equivalence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Description of status/issues in mapping */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapGroupElementTargetDependsOn> dependsOn Other elements required for this mapping (from context) */
		public array $dependsOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRConceptMapGroupElementTargetDependsOn> product Other concepts that this mapping also produces */
		public array $product = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
