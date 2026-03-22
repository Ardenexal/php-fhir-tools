<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A description of the precision of the estimate for the effect.
 */
#[FHIRBackboneElement(
    parentResource: 'RiskEvidenceSynthesis',
    elementPath: 'RiskEvidenceSynthesis.riskEstimate.precisionEstimate',
    fhirVersion: 'R4',
)]
class RiskEvidenceSynthesisRiskEstimatePrecisionEstimate extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of precision estimate */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var numeric-string|null level Level of confidence interval */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $level = null,
        /** @var numeric-string|null from Lower bound */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $from = null,
        /** @var numeric-string|null to Upper bound */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $to = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
