<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / Community Based Collaborative Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint
 *
 * @description Endpoint for sending Disclosure notifications in the form of FHIR AuditEvent records.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint', fhirVersion: 'R5')]
class ConsentNotificationEndpointExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $valueUri = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint',
            value: $this->valueUri,
        );
    }
}
