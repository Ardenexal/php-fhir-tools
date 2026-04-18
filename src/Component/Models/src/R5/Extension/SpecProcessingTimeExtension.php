<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/specimen-processingTime
 *
 * @description Period or duration of processing. This is no longer relevant for R6 due to a matching element in R6. This extension is now deprecated.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime', fhirVersion: 'R5')]
class SpecProcessingTimeExtension extends Extension
{
    public function __construct(
        /** @var Period|Duration|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        Period|Duration|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime',
            value: $value,
        );
    }
}
