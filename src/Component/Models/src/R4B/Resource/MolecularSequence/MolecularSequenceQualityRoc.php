<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4B')]
class MolecularSequenceQualityRoc extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'score' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'numTP' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'numFP' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'numFN' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'precision' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'sensitivity' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'fMeasure' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<int> score Genotype quality score */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $score = [],
        /** @var array<int> numTP Roc score true positive numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numTP = [],
        /** @var array<int> numFP Roc score false positive numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numFP = [],
        /** @var array<int> numFN Roc score false negative numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numFN = [],
        /** @var array<float> precision Precision of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $precision = [],
        /** @var array<float> sensitivity Sensitivity of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $sensitivity = [],
        /** @var array<float> fMeasure FScore of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $fMeasure = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
