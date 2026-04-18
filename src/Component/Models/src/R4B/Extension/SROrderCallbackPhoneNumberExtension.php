<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number
 *
 * @description This extension contains the phone number for reporting a status or a result. This is represented in v2 as OBR-17 Order Callback Phone Number ID 00250.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number', fhirVersion: 'R4B')]
class SROrderCallbackPhoneNumberExtension extends Extension
{
    public function __construct(
        /** @var ContactPoint|null valueContactPoint Value of extension */
        #[FhirProperty(fhirType: 'ContactPoint', propertyKind: 'complex')]
        public ?ContactPoint $valueContactPoint = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number',
            value: $this->valueContactPoint,
        );
    }
}
