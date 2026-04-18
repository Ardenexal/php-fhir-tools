<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/timing-uncertainDate
 *
 * @description When present, this extension indicates that the date of a period start or end is uncertain and falls within its own period.  DEPRECATED: use [uncertainPeriod](StructureDefinition-uncertainPeriod.html) instead.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timing-uncertainDate', fhirVersion: 'R4B')]
class UncertainDateExtension extends Extension
{
    public function __construct(
        /** @var Period|null valuePeriod Value of extension */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $valuePeriod = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/timing-uncertainDate',
            value: $this->valuePeriod,
        );
    }
}
