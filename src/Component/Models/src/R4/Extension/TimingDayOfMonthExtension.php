<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/timing-dayOfMonth
 *
 * @description When present, this extension indicates that the event actually only occurs on the specified days of the month, on the times as otherwise specified by the timing schedule.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timing-dayOfMonth', fhirVersion: 'R4')]
class TimingDayOfMonthExtension extends Extension
{
    public function __construct(
        /** @var PositiveIntPrimitive|null valuePositiveInt Value of extension */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $valuePositiveInt = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/timing-dayOfMonth',
            value: $this->valuePositiveInt,
        );
    }
}
