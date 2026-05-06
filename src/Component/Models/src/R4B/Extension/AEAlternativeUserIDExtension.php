<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;

/**
 * @author HL7 International / Security
 *
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID
 *
 * @description An AlternativeUserID Number associated with this participant object.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID', fhirVersion: 'R4B')]
class AEAlternativeUserIDExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID',
            value: $this->valueIdentifier,
        );
    }
}
