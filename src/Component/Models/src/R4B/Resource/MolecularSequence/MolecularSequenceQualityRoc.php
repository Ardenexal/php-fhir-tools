<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DecimalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IntegerPrimitive;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4B')]
class MolecularSequenceQualityRoc extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var array<IntegerPrimitive|int> score Genotype quality score */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $score = [],
        /** @var array<IntegerPrimitive|int> numTP Roc score true positive numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numTP = [],
        /** @var array<IntegerPrimitive|int> numFP Roc score false positive numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numFP = [],
        /** @var array<IntegerPrimitive|int> numFN Roc score false negative numbers */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $numFN = [],
        /** @var array<DecimalPrimitive|numeric-string> precision Precision of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $precision = [],
        /** @var array<DecimalPrimitive|numeric-string> sensitivity Sensitivity of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $sensitivity = [],
        /** @var array<DecimalPrimitive|numeric-string> fMeasure FScore of the GQ score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true)]
        public array $fMeasure = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
