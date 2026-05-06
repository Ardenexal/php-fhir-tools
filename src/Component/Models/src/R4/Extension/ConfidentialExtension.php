<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/confidential
 *
 * @description Expresses the access policy associated with the element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/confidential', fhirVersion: 'R4')]
class ConfidentialExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|CodeableConcept|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        UriPrimitive|CodeableConcept|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/confidential',
            value: $value,
        );
    }
}
