<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/bodySite
 *
 * @description Record details about the anatomical location of a specimen or body part. This resource may be used when a coded concept does not provide the necessary detail needed for the use case.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/bodySite', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition.bodySite')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation.bodySite')]
#[FHIRExtensionContext(type: 'element', expression: 'Procedure.bodySite')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationAdministration.dosage')]
#[FHIRExtensionContext(type: 'element', expression: 'Dosage.site')]
#[FHIRExtensionContext(type: 'element', expression: 'Specimen.collection')]
class BodyStructureReferenceExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/bodySite',
            value: $this->valueReference,
        );
    }
}
