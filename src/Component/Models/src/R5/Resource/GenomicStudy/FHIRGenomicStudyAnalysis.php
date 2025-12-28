<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The details about a specific analysis that was performed in this GenomicStudy.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GenomicStudy', elementPath: 'GenomicStudy.analysis', fhirVersion: 'R5')]
class FHIRGenomicStudyAnalysis extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Identifiers for the analysis event */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> methodType Type of the methods used in the analysis (e.g., FISH, Karyotyping, MSI) */
		public array $methodType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> changeType Type of the genomic changes studied in the analysis (e.g., DNA, RNA, or AA change) */
		public array $changeType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept genomeBuild Genome build that is used in this analysis */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $genomeBuild = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical instantiatesCanonical The defined protocol that describes the analysis */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri instantiatesUri The URL pointing to an externally maintained protocol that describes the analysis */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $instantiatesUri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Name of the analysis event (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> focus What the genomic analysis is about, when it is not about the subject of record */
		public array $focus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> specimen The specimen used in the analysis event */
		public array $specimen = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime date The date of the analysis event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $date = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Any notes capture with the analysis event */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference protocolPerformed The protocol that was performed for the analysis event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $protocolPerformed = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> regionsStudied The genomic regions to be studied in the analysis (BED file) */
		public array $regionsStudied = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> regionsCalled Genomic regions actually called in the analysis event (BED file) */
		public array $regionsCalled = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGenomicStudyAnalysisInput> input Inputs for the analysis event */
		public array $input = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGenomicStudyAnalysisOutput> output Outputs for the analysis event */
		public array $output = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGenomicStudyAnalysisPerformer> performer Performer for the analysis event */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGenomicStudyAnalysisDevice> device Devices used for the analysis (e.g., instruments, software), with settings and parameters */
		public array $device = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
