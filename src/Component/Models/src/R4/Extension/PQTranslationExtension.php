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
 * @see http://hl7.org/fhir/StructureDefinition/iso21090-PQ-translation
 *
 * @description An alternative representation of the same physical quantity expressed in a different unit from a different unit code system and possibly with a different value.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/iso21090-PQ-translation', fhirVersion: 'R4')]
class PQTranslationExtension extends Extension
{
    public function __construct(
        /** @var Quantity|null valueQuantity Value of extension */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $valueQuantity = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/iso21090-PQ-translation',
            value: $this->valueQuantity,
        );
    }
}
