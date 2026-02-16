<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QualityTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality', fhirVersion: 'R4')]
class MolecularSequenceQuality extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var QualityTypeType|null type indel | snp | unknown */
        #[NotBlank]
        public ?QualityTypeType $type = null,
        /** @var CodeableConcept|null standardSequence Standard sequence for comparison */
        public ?CodeableConcept $standardSequence = null,
        /** @var int|null start Start position of the sequence */
        public ?int $start = null,
        /** @var int|null end End position of the sequence */
        public ?int $end = null,
        /** @var Quantity|null score Quality score for the comparison */
        public ?Quantity $score = null,
        /** @var CodeableConcept|null method Method to get quality */
        public ?CodeableConcept $method = null,
        /** @var float|null truthTP True positives from the perspective of the truth data */
        public ?float $truthTP = null,
        /** @var float|null queryTP True positives from the perspective of the query data */
        public ?float $queryTP = null,
        /** @var float|null truthFN False negatives */
        public ?float $truthFN = null,
        /** @var float|null queryFP False positives */
        public ?float $queryFP = null,
        /** @var float|null gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
        public ?float $gtFP = null,
        /** @var float|null precision Precision of comparison */
        public ?float $precision = null,
        /** @var float|null recall Recall of comparison */
        public ?float $recall = null,
        /** @var float|null fScore F-score */
        public ?float $fScore = null,
        /** @var MolecularSequenceQualityRoc|null roc Receiver Operator Characteristic (ROC) Curve */
        public ?MolecularSequenceQualityRoc $roc = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
