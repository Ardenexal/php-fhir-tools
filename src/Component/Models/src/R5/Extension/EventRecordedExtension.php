<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/event-recorded
 *
 * @description The date the occurrence of the event was first captured in the record - potentially significantly after the occurrence of the event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/event-recorded', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Communication')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
class EventRecordedExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|null valueDateTime Value of extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $valueDateTime = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/event-recorded',
            value: $this->valueDateTime,
        );
    }
}
