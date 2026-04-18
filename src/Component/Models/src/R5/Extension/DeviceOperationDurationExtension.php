<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/device-operation-duration
 *
 * @description Length of time of the device's operation (e.g., days, hours, mins, etc.).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-operation-duration', fhirVersion: 'R5')]
class DeviceOperationDurationExtension extends Extension
{
    public function __construct(
        /** @var Duration|null valueDuration Value of extension */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $valueDuration = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/device-operation-duration',
            value: $this->valueDuration,
        );
    }
}
