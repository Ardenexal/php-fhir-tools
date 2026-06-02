<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Ratio
 *
 * @description A relationship of two Quantity values - expressed as a numerator and a denominator.
 */
#[FHIRComplexType(typeName: 'Ratio', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'rat-1',
    severity: 'error',
    expression: '(numerator.exists() and denominator.exists()) or (numerator.empty() and denominator.empty() and extension.exists())',
    human: 'Numerator and denominator SHALL both be present, or both are absent. If both are absent, there SHALL be some extension present',
)]
class Ratio extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Quantity|null numerator Numerator value */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $numerator = null,
        /** @var Quantity|null denominator Denominator value */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $denominator = null,
    ) {
        parent::__construct($id, $extension);
    }
}
