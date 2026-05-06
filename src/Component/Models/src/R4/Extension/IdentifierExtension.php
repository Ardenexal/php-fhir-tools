<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-identifier
 *
 * @description External Identifiers associated with this element - these are identifiers that are associated with the concept this element represents. DEPRECATED: Use either ElementDefinition.code or ElementDefinition.mapping to provide the mapping instead
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-identifier', fhirVersion: 'R4')]
class IdentifierExtension extends Extension
{
    public function __construct(
        /** @var Identifier|null valueIdentifier Value of extension */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $valueIdentifier = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-identifier',
            value: $this->valueIdentifier,
        );
    }
}
