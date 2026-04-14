<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsGenomicSourceClass
 *
 * @description The material on this page will be removed in a future release. This content is deprecated and SHOULD NOT be used. Implementers are instead directed to the ([Genomics Reporting Implementation Guide](http://hl7.org/fhir/uv/genomics-reporting/index.html)) for guidance. Source of sample used to determine the sequence in sequencing lab -- germline, somatic, prenatal. LOINC Code: ([48002-0](http://loinc.org/48002-0)).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsGenomicSourceClass', fhirVersion: 'R4B')]
class GenomicSourceClassExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsGenomicSourceClass',
            value: $this->valueCodeableConcept,
        );
    }
}
