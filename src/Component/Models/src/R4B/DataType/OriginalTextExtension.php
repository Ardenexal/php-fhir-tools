<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/originalText
 *
 * @description A human language representation of the concept (resource/element) as seen/selected/uttered by the user who entered the data and/or which represents the full intended meaning of the user. This can be provided either directly as text, or as a url that is a reference to a portion of the narrative of a resource ([DomainResource.text](narrative.html)).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/originalText', fhirVersion: 'R4B')]
class OriginalTextExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/originalText',
            value: $this->valueString,
        );
    }
}
