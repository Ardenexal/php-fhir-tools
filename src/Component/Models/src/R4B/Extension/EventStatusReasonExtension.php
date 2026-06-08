<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/event-statusReason
 *
 * @description Captures the reason for the current state of the resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/event-statusReason', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUsage')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement')]
class EventStatusReasonExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/event-statusReason',
            value: $this->valueCodeableConcept,
        );
    }
}
