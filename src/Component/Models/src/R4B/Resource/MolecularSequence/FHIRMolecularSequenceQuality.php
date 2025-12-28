<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality', fhirVersion: 'R4B')]
class FHIRMolecularSequenceQuality extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQualityTypeType|null type indel | snp | unknown */
        #[NotBlank]
        public ?\FHIRQualityTypeType $type = null,
        /** @var FHIRCodeableConcept|null standardSequence Standard sequence for comparison */
        public ?\FHIRCodeableConcept $standardSequence = null,
        /** @var FHIRInteger|null start Start position of the sequence */
        public ?\FHIRInteger $start = null,
        /** @var FHIRInteger|null end End position of the sequence */
        public ?\FHIRInteger $end = null,
        /** @var FHIRQuantity|null score Quality score for the comparison */
        public ?\FHIRQuantity $score = null,
        /** @var FHIRCodeableConcept|null method Method to get quality */
        public ?\FHIRCodeableConcept $method = null,
        /** @var FHIRDecimal|null truthTP True positives from the perspective of the truth data */
        public ?\FHIRDecimal $truthTP = null,
        /** @var FHIRDecimal|null queryTP True positives from the perspective of the query data */
        public ?\FHIRDecimal $queryTP = null,
        /** @var FHIRDecimal|null truthFN False negatives */
        public ?\FHIRDecimal $truthFN = null,
        /** @var FHIRDecimal|null queryFP False positives */
        public ?\FHIRDecimal $queryFP = null,
        /** @var FHIRDecimal|null gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
        public ?\FHIRDecimal $gtFP = null,
        /** @var FHIRDecimal|null precision Precision of comparison */
        public ?\FHIRDecimal $precision = null,
        /** @var FHIRDecimal|null recall Recall of comparison */
        public ?\FHIRDecimal $recall = null,
        /** @var FHIRDecimal|null fScore F-score */
        public ?\FHIRDecimal $fScore = null,
        /** @var FHIRMolecularSequenceQualityRoc|null roc Receiver Operator Characteristic (ROC) Curve */
        public ?\FHIRMolecularSequenceQualityRoc $roc = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
