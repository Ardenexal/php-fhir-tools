<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces
 *
 * @description Identifies the maximum number of decimal places that may be specified for the data element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces', fhirVersion: 'R4B')]
class MaxDecimalPlacesExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
            value: $this->valueInteger,
        );
    }
}
