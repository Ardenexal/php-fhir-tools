<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Inputs for the analysis event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GenomicStudy', elementPath: 'GenomicStudy.analysis.input', fhirVersion: 'R5')]
class FHIRGenomicStudyAnalysisInput extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference file File containing input data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $file = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Type of input data (e.g., BAM, CRAM, or FASTA) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference generatedByX The analysis event or other GenomicStudy that generated this input file */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $generatedByX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
