<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceReferenceInformation', elementPath: 'SubstanceReferenceInformation.gene', fhirVersion: 'R4B')]
class FHIRSubstanceReferenceInformationGene extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept geneSequenceOrigin Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $geneSequenceOrigin = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept gene Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $gene = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> source Todo */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
