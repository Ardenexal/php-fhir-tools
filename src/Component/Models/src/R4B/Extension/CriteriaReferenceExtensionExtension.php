<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Quality Information
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-criteriaReference
 *
 * @description Specifies which criteria is used as the input for calculations when there are multiple populations of the same type (such as continuous variable and ratio measures). The extension can also appear on evaluated resources and supplemental data to indicate which criteria the resource is associated with (i.e. which criteria was responsible for the reference to the resource). The reference SHALL be to the linkId of a criteria within the referenced measure. For backwards-compatibility, systems MAY support references to the id element of the criteria.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-criteriaReference', fhirVersion: 'R4B')]
class CriteriaReferenceExtensionExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-criteriaReference',
            value: $this->valueString,
        );
    }
}
