<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/procedure-schedule
 *
 * @description The schedule that was followed.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-schedule', fhirVersion: 'R4B')]
class ScheduleExtension extends Extension
{
    public function __construct(
        /** @var Timing|null valueTiming Value of extension */
        #[FhirProperty(fhirType: 'Timing', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing $valueTiming = null,
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
