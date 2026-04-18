<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/no-binding
 *
 * @description Marks that a particular element definition is one that is not expected/allowed to have a binding, even though it has types that may be bound to value sets.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/no-binding', fhirVersion: 'R4B')]
class HttpHl7OrgFhirStructureDefinitionNoBindingExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/no-binding',
            value: $this->valueBoolean,
        );
    }
}
