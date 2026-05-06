<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/quantity-precision
 *
 * @description Explicit precision of the number. This is the number of significant decimal places after the decimal point, irrespective of how many are actually present in the explicitly represented decimal.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/quantity-precision', fhirVersion: 'R4B')]
class PrecisionExtension extends Extension
{
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/quantity-precision',
            value: $this->valueInteger,
        );
    }
}
