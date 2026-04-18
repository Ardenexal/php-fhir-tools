<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/organization-period
 *
 * @description The date range that this organization should be considered available.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/organization-period', fhirVersion: 'R4B')]
class OrgPeriodExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/organization-period',
            value: $this->valuePeriod,
        );
    }
}
