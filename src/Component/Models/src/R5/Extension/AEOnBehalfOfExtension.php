<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Security
 *
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-OnBehalfOf
 *
 * @description When an AuditEvent is attributed to an agent that is acting on behalf of another agent. Typically needed when multiple agents are acting on behalf of different organizations, and when PractitionerRole is not appropriate.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-OnBehalfOf', fhirVersion: 'R5')]
class AEOnBehalfOfExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/auditevent-OnBehalfOf',
            value: $this->valueReference,
        );
    }
}
