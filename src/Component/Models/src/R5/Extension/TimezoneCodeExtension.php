<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/tz-code
 *
 * @description An IANA timezone code for  the timezone offset per [BCP 175](https://www.iana.org/go/rfc6557). The offset is specified as part of a dateTime/instant (or using the tzOffset extension on a date if necessary). The timezone code may also be provided to allow for human display of the location associated with the offset.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/tz-code', fhirVersion: 'R5')]
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
            url: 'http://hl7.org/fhir/StructureDefinition/tz-code',
            value: $this->valueCode,
        );
    }
}
