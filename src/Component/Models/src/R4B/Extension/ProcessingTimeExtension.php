<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/specimen-processingTime
 *
 * @description Period or duration of processing.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime', fhirVersion: 'R4B')]
class ProcessingTimeExtension extends Extension
{
    public function __construct(
        /** @var Period|Duration|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime',
            value: $this->value,
        );
    }
}
