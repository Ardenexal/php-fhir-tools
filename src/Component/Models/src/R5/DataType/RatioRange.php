<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RatioRange
 *
 * @description A range of ratios expressed as a low and high numerator and a denominator.
 */
#[FHIRComplexType(typeName: 'RatioRange', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'ratrng-1',
    severity: 'error',
    expression: '((lowNumerator.exists() or highNumerator.exists()) and denominator.exists()) or (lowNumerator.empty() and highNumerator.empty() and denominator.empty() and extension.exists())',
    human: 'One of lowNumerator or highNumerator and denominator SHALL be present, or all are absent. If all are absent, there SHALL be some extension present',
)]
#[FHIRPathInvariant(
    key: 'ratrng-2',
    severity: 'error',
    expression: 'lowNumerator.hasValue().not() or highNumerator.hasValue().not()  or (lowNumerator.lowBoundary() <= highNumerator.highBoundary())',
    human: 'If present, lowNumerator SHALL have a lower value than highNumerator',
)]
class RatioRange extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Quantity|null lowNumerator Low Numerator limit */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $lowNumerator = null,
        /** @var Quantity|null highNumerator High Numerator limit */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $highNumerator = null,
        /** @var Quantity|null denominator Denominator value */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $denominator = null,
    ) {
        parent::__construct($id, $extension);
    }
}
