<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-globalLangPack
 *
 * @description Marks a supplement as a global language pack - that is, that the language designations provided in the supplement are intended for general use with that language for all valuesets and code systems.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-globalLangPack', fhirVersion: 'R5')]
class CodeSystemGlobalLangPackExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-globalLangPack',
            value: $this->valueBoolean,
        );
    }
}
