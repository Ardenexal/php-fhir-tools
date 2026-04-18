<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-periodDuration
 *
 * @description Specifies the duration of the period, based on the starting date, rather than specifying the end date of the period.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-periodDuration', fhirVersion: 'R4')]
class PeriodDurationExtension extends Extension
{
    public function __construct(
        /** @var Duration|null valueDuration Value of extension */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $valueDuration = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-periodDuration',
            value: $this->valueDuration,
        );
    }
}
