<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-unclosed
 *
 * @description Marks that the expansion is incomplete, and values other than those listed may be valid. This may be used when technical limitations prevent a full expansion, or when post-coordinated codes are allowed, and no complete expansion can be produced.  This extension SHALL only be used when the client specifies that it will recognize and process an incomplete expansion. If the client has not specified it will recognize and process an incomplete expansion, the server SHALL return an error.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-unclosed', fhirVersion: 'R4B')]
class VSUnclosedExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-unclosed',
            value: $this->valueBoolean,
        );
    }
}
