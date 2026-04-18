<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/organization-preferredContact
 *
 * @description This Contact is the preferred contact at this organization for the purpose of the contact.
 *
 * There can be multiple contacts on an Organizations record with this value set to true, but these should all have different purpose values.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/organization-preferredContact', fhirVersion: 'R4')]
class OrgPreferredContactExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/organization-preferredContact',
            value: $this->valueBoolean,
        );
    }
}
