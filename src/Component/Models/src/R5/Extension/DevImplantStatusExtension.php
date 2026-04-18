<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/device-implantStatus
 *
 * @description Codes to represent the functional status of a device implanted in a patient.  Both overall device status and an implant status need to be considered. The implant status should only be used when the [device status](device-definitions.html#Device.status) is `active `.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/device-implantStatus', fhirVersion: 'R5')]
class DevImplantStatusExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/device-implantStatus',
            value: $this->valueCode,
        );
    }
}
