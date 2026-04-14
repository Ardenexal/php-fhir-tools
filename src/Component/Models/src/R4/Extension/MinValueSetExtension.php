<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-minValueSet
 *
 * @description The minimum allowable value set, for use when the binding strength is 'required' or 'extensible'. This value set is the minimum value set that any conformant system SHALL support.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-minValueSet', fhirVersion: 'R4')]
class MinValueSetExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-minValueSet',
            value: $this->value,
        );
    }
}
