<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MolecularSequence.quality
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
class FHIRMolecularSequenceQuality extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQualityTypeType type indel | snp | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQualityTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept standardSequence Standard sequence for comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $standardSequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger start Start position of the sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger end End position of the sequence */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity score Quality score for the comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity $score = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept method Method to get quality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal truthTP True positives from the perspective of the truth data */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $truthTP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal queryTP True positives from the perspective of the query data */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $queryTP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal truthFN False negatives */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $truthFN = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal queryFP False positives */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $queryFP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $gtFP = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal precision Precision of comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $precision = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal recall Recall of comparison */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $recall = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal fScore F-score */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $fScore = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMolecularSequenceQualityRoc roc Receiver Operator Characteristic (ROC) Curve */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMolecularSequenceQualityRoc $roc = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
