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
 * @see http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod
 *
 * @description The period during which the resource content was or is planned to be effective.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod', fhirVersion: 'R4B')]
class ResourceEffectivePeriodExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod',
            value: $this->valuePeriod,
        );
    }
}
