<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-caseSensitive
 *
 * @description An assertion of whether the code is case sensitive or not. This assertion SHALL be based on how the code system is defined. If the server generating the expansion does not know how the code system defined case sensitivity of codes, this extension SHALL NOT be used.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-caseSensitive', fhirVersion: 'R5')]
class VSCaseSensitiveExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-caseSensitive',
            value: $this->valueBoolean,
        );
    }
}
