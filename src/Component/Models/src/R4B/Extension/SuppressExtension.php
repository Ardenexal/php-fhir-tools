<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-suppress
 *
 * @description If the extension is present on one of the named properties in a differential, the element property should be removed from the the corresponding snapshot.element during snapshot generation.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-suppress', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.comment')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.requirements')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.alias')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.example')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.mapping')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.label')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.code')]
class SuppressExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-suppress',
            value: $this->valueBoolean,
        );
    }
}
