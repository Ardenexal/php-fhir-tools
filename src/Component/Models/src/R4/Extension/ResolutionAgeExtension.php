<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-resolutionAge
 *
 * @description The estimated patient age at which the allergy or intolerance resolved. Should be specified only if the status is resolved.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-resolutionAge', fhirVersion: 'R4')]
class ResolutionAgeExtension extends Extension
{
    public function __construct(
        /** @var Age|null valueAge Value of extension */
        #[FhirProperty(fhirType: 'Age', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age $valueAge = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-resolutionAge',
            value: $this->valueAge,
        );
    }
}
