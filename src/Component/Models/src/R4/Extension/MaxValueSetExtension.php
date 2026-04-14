<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet
 *
 * @description The maximum allowable value set, for use when the binding strength is 'extensible' or 'preferred'. This value set is the value set from which additional codes can be taken from. This defines a 'required' binding over the top of the extensible binding.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet', fhirVersion: 'R4')]
class MaxValueSetExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|CanonicalPrimitive|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet',
            value: $this->value,
        );
    }
}
