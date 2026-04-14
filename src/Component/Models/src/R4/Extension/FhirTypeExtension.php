<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type
 *
 * @description The formal FHIR type of a property - used for the value property of a primitive type (for which there is no type in the FHIR typing system), and Element.id and Extension.url.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type', fhirVersion: 'R4')]
class FhirTypeExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $valueUrl = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fhir-type',
            value: $this->valueUrl,
        );
    }
}
