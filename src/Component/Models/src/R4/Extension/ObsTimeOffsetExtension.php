<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-timeOffset
 *
 * @description A specific offset time in milliseconds from the stated time in the Observation.appliesDateTime to allow for representation of sequential recording  of sampled data from the same lead or data stream.  For example, an ECG recorder may record sequentially 3 leads four time to receive 12-lead ECG, see [ISO 22077](https://www.iso.org/obp/ui/#iso:std:61871:en).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-timeOffset', fhirVersion: 'R4')]
class ObsTimeOffsetExtension extends Extension
{
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-timeOffset',
            value: $this->valueInteger,
        );
    }
}
