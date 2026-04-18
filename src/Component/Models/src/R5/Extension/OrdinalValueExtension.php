<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/ordinalValue
 *
 * @description A numeric value that allows the comparison (less than, greater than) or other numerical
 * manipulation of a concept (e.g. Adding up components of a score). Scores are usually a whole number, but occasionally decimals are encountered in scores.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/ordinalValue', fhirVersion: 'R5')]
class OrdinalValueExtension extends Extension
{
    public function __construct(
        /** @var string|null valueDecimal Value of extension */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $valueDecimal = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/ordinalValue',
            value: $this->valueDecimal,
        );
    }
}
