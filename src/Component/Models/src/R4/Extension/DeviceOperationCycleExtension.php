<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Count;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/device-operation-cycle
 *
 * @description Count of operating cycles, e.g., the number of measurements taken by the device.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-operation-cycle', fhirVersion: 'R4')]
class DeviceOperationCycleExtension extends Extension
{
    public function __construct(
        /** @var Count|null valueCount Value of extension */
        #[FhirProperty(fhirType: 'Count', propertyKind: 'complex')]
        public ?Count $valueCount = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/device-operation-cycle',
            value: $this->valueCount,
        );
    }
}
