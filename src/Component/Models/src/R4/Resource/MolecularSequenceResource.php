<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 * @see http://hl7.org/fhir/StructureDefinition/MolecularSequence
 * @description Raw data describing a biological sequence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MolecularSequence',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
	fhirVersion: 'R4',
)]
class MolecularSequenceResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Unique ID for this particular sequence. This is a FHIR-defined id */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SequenceTypeType type aa | dna | rna */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SequenceTypeType $type = null,
		/** @var null|int coordinateSystem Base number of coordinate system (0 for 0-based numbering or coordinates, inclusive start, exclusive end, 1 for 1-based numbering, inclusive start, inclusive end) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?int $coordinateSystem = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Who and/or what this is about */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference specimen Specimen used for sequencing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $specimen = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference device The method for sequencing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $device = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performer Who should be responsible for test result */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity The number of copies of the sequence of interest.  (RNASeq) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceReferenceSeq referenceSeq A sequence used as reference */
		public ?MolecularSequence\MolecularSequenceReferenceSeq $referenceSeq = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceVariant> variant Variant in sequence */
		public array $variant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string observedSeq Sequence that was observed */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $observedSeq = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceQuality> quality An set of value as quality of sequence */
		public array $quality = [],
		/** @var null|int readCoverage Average number of reads representing a given nucleotide in the reconstructed sequence */
		public ?int $readCoverage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceRepository> repository External repository which contains detailed report related with observedSeq in this resource */
		public array $repository = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> pointer Pointer to next atomic sequence */
		public array $pointer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceStructureVariant> structureVariant Structural variant */
		public array $structureVariant = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
