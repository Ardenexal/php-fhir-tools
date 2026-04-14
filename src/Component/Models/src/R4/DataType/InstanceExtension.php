<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - Security WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-Instance
 *
 * @description Th SOP Instance UID values.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-Instance', fhirVersion: 'R4')]
class InstanceExtension extends Extension
{
    public function __construct(
        /** @var Identifier|null valueIdentifier Value of extension */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $valueIdentifier = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/auditevent-Instance',
            value: $this->valueIdentifier,
        );
    }
}
