<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Community Based Collaborative Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-location
 *
 * @description Restricts this exception to only apply a specific location as defined.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-location', fhirVersion: 'R5')]
class ConsentLocationExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/consent-location',
            value: $this->valueReference,
        );
    }
}
