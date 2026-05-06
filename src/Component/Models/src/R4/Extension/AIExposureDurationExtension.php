<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/openEHR-exposureDuration
 *
 * @description The amount of time the individual was exposed to the Substance.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/openEHR-exposureDuration', fhirVersion: 'R4')]
class AIExposureDurationExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/openEHR-exposureDuration',
            value: $this->valueDuration,
        );
    }
}
