<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Claim.related
 * @description Other claims which are related to this claim such as prior submissions or claims for related services or for the same event.
 */
class FHIRClaimRelated extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference claim Reference to the related claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $claim = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept relationship How the reference claim is related */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier reference File or case reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $reference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
