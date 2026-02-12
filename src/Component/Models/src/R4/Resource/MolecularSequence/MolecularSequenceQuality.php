<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

/**
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality', fhirVersion: 'R4')]
class MolecularSequenceQuality extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\QualityTypeType type indel | snp | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\QualityTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept standardSequence Standard sequence for comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $standardSequence = null,
		/** @var null|int start Start position of the sequence */
		public ?int $start = null,
		/** @var null|int end End position of the sequence */
		public ?int $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity score Quality score for the comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $score = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method Method to get quality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
		/** @var null|float truthTP True positives from the perspective of the truth data */
		public ?float $truthTP = null,
		/** @var null|float queryTP True positives from the perspective of the query data */
		public ?float $queryTP = null,
		/** @var null|float truthFN False negatives */
		public ?float $truthFN = null,
		/** @var null|float queryFP False positives */
		public ?float $queryFP = null,
		/** @var null|float gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
		public ?float $gtFP = null,
		/** @var null|float precision Precision of comparison */
		public ?float $precision = null,
		/** @var null|float recall Recall of comparison */
		public ?float $recall = null,
		/** @var null|float fScore F-score */
		public ?float $fScore = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceQualityRoc roc Receiver Operator Characteristic (ROC) Curve */
		public ?MolecularSequenceQualityRoc $roc = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
