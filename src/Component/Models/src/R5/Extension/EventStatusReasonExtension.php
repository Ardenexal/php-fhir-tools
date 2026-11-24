<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/event-statusReason
 *
 * @description Captures the reason for the current state of the resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/event-statusReason', fhirVersion: 'R5', isModifier: false)]
#[FHIRExtensionContext(type: 'element', expression: 'Observation', endFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport', endFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUsage', startFhirVersion: '5.0')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement', endFhirVersion: '4.3')]
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
