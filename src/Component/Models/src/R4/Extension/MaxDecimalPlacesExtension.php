<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces
 *
 * @description Identifies the maximum number of decimal places that may be specified for the data element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces', fhirVersion: 'R4')]
class MaxDecimalPlacesExtension extends Extension
{
    public function __construct(
        /** @var int|Quantity|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        int|Quantity|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
            value: $value,
        );
    }
}
