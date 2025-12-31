<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality', fhirVersion: 'R4')]
class FHIRMolecularSequenceQuality extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQualityTypeType type indel | snp | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQualityTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept standardSequence Standard sequence for comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $standardSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger start Start position of the sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger end End position of the sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity score Quality score for the comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $score = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept method Method to get quality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal truthTP True positives from the perspective of the truth data */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $truthTP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal queryTP True positives from the perspective of the query data */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $queryTP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal truthFN False negatives */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $truthFN = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal queryFP False positives */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $queryFP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $gtFP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal precision Precision of comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $precision = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal recall Recall of comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $recall = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal fScore F-score */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $fScore = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMolecularSequenceQualityRoc roc Receiver Operator Characteristic (ROC) Curve */
		public ?FHIRMolecularSequenceQualityRoc $roc = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
