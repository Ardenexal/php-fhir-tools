<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4B')]
class FHIRMolecularSequenceQualityRoc extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRInteger> score Genotype quality score */
        public array $score = [],
        /** @var array<FHIRInteger> numTP Roc score true positive numbers */
        public array $numTP = [],
        /** @var array<FHIRInteger> numFP Roc score false positive numbers */
        public array $numFP = [],
        /** @var array<FHIRInteger> numFN Roc score false negative numbers */
        public array $numFN = [],
        /** @var array<FHIRDecimal> precision Precision of the GQ score */
        public array $precision = [],
        /** @var array<FHIRDecimal> sensitivity Sensitivity of the GQ score */
        public array $sensitivity = [],
        /** @var array<FHIRDecimal> fMeasure FScore of the GQ score */
        public array $fMeasure = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
