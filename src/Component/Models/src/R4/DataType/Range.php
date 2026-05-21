<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Range
 *
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[FHIRComplexType(typeName: 'Range', fhirVersion: 'R4')]
#[FHIRPathInvariant(
    key: 'rng-2',
    severity: 'error',
    expression: 'low.empty() or high.empty() or (low <= high)',
    human: 'If present, low SHALL have a lower value than high',
)]
class Range extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Quantity|null low Low limit */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $low = null,
        /** @var Quantity|null high High limit */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $high = null,
    ) {
        parent::__construct($id, $extension);
    }
}
