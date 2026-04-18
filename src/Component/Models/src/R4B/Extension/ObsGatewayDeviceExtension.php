<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-gatewayDevice
 *
 * @description To be used when the actual gateway used at the time of the observation, and the referenced device is also specified. The gateway device may be acting as a communication/data collector, translator or controller; This extension is useful when there is more than one gateway device, for example, where there are apps on a phone and each are a device, and more than one app is used to pass on the data to a FHIR Server. In that case you need to be able to say from the observation which specific app was used to act as gateway. Note that the Provenance/AuditEvent resources can represent the same information. Use Device Gateway (device-gateway) extension which allows codeableReference (Device) in R6 and requires cross version extension for codeable reference.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-gatewayDevice', fhirVersion: 'R4B')]
class ObsGatewayDeviceExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/observation-gatewayDevice',
            value: $this->valueReference,
        );
    }
}
