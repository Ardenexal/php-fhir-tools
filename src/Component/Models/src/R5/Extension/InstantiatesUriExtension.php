<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-instantiatesUri
 *
 * @description The URL pointing to an externally maintained protocol, guideline, orderset or other definition that is adhered to in whole or in part by the event or request resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-instantiatesUri', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'Media')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
class InstantiatesUriExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-instantiatesUri',
            value: $this->valueUri,
        );
    }
}
