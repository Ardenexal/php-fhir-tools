<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/procedure-schedule
 *
 * @description The schedule that was followed. Use Procedure.occurrenceTiming in R5+
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-schedule', fhirVersion: 'R4')]
class ProcedureScheduleExtension extends Extension
{
    public function __construct(
        /** @var Timing|null valueTiming Value of extension */
        #[FhirProperty(fhirType: 'Timing', propertyKind: 'complex')]
        public ?Timing $valueTiming = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/procedure-schedule',
            value: $this->valueTiming,
        );
    }
}
