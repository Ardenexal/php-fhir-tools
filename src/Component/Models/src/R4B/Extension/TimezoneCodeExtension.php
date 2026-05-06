<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/timezone
 *
 * @description An IANA timezone code for the timezone offset per BCP 175.
 * For data types allowing the offset (all except date and time), this extension SHALL agree with the offset if provided and cannot be used in place of the offset if the precision expressed requires an offset.  Where an offset is provided, this extension provides a more legible display of the zone associated with the offset.  Where an offset is not present, this extension can be used to provide zone information not otherwise available.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timezone', fhirVersion: 'R4B')]
class TimezoneCodeExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/timezone',
            value: $this->valueCode,
        );
    }
}
