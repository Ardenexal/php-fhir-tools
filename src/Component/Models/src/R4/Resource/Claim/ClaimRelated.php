<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

/**
 * @description Other claims which are related to this claim such as prior submissions or claims for related services or for the same event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.related', fhirVersion: 'R4')]
class ClaimRelated extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference claim Reference to the related claim */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $claim = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept relationship How the reference claim is related */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier reference File or case reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $reference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
