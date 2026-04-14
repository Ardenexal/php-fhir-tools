<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration
 *
 * @description The amount of time that the Adverse Reaction persisted.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration', fhirVersion: 'R4B')]
class DurationExtension extends Extension
{
    public function __construct(
        /** @var Duration|null valueDuration Value of extension */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration $valueDuration = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration',
            value: $this->valueDuration,
        );
    }
}
