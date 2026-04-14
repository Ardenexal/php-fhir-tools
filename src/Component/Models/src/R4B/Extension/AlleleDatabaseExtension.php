<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-allele-database
 *
 * @description Allele Database.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-allele-database', fhirVersion: 'R4B')]
class AlleleDatabaseExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-allele-database',
            value: $this->valueCodeableConcept,
        );
    }
}
