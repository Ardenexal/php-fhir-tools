<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4')]
class MolecularSequenceQualityRoc extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<int> score Genotype quality score */
        public array $score = [],
        /** @var array<int> numTP Roc score true positive numbers */
        public array $numTP = [],
        /** @var array<int> numFP Roc score false positive numbers */
        public array $numFP = [],
        /** @var array<int> numFN Roc score false negative numbers */
        public array $numFN = [],
        /** @var array<float> precision Precision of the GQ score */
        public array $precision = [],
        /** @var array<float> sensitivity Sensitivity of the GQ score */
        public array $sensitivity = [],
        /** @var array<float> fMeasure FScore of the GQ score */
        public array $fMeasure = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
