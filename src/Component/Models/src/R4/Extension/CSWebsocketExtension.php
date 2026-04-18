<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-websocket
 *
 * @description Where the server provides its web socket end-point. Note: this extension is deprecated because it's poorly defined and is also over-constrained to just 1 occurrence. If there is a need for an extension like this as a common extension, please submit a change request and we will create one that is better defined, clear about its purpose, and has an appropriate cardinality.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-websocket', fhirVersion: 'R4')]
class CSWebsocketExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-websocket',
            value: $this->valueUri,
        );
    }
}
