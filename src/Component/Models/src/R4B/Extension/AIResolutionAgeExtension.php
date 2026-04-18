<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-resolutionAge
 *
 * @description The estimated patient age at which the allergy or intolerance resolved. Should be specified only if the status is resolved. This extension is deprecated and replaced by allergyintolerance-abatement.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-resolutionAge', fhirVersion: 'R4B')]
class AIResolutionAgeExtension extends Extension
{
    public function __construct(
        /** @var Age|null valueAge Value of extension */
        #[FhirProperty(fhirType: 'Age', propertyKind: 'complex')]
        public ?Age $valueAge = null,
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
