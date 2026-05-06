<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-prohibited
 *
 * @description If set to true, indicates that support for the specified behavior would make a system non-conformant with the specification.  See the capabilitystatement-expectation extension to set non-prohibited expectations.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-prohibited', fhirVersion: 'R4B')]
class CSProhibitedExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Value of extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-prohibited',
            value: $this->valueBoolean,
        );
    }
}
