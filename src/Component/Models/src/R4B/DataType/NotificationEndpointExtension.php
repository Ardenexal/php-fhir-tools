<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - CBCC WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint
 *
 * @description Endpoint for sending Disclosure notifications in the form of FHIR AuditEvent records.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint', fhirVersion: 'R4B')]
class NotificationEndpointExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $valueUri = null,
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
