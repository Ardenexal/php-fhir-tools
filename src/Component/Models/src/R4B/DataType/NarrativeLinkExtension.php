<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/narrativeLink
 *
 * @description A human language representation of the concept (resource/element), as a url that is a reference to a portion of the narrative of a resource ([DomainResource.text](narrative.html)).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/narrativeLink', fhirVersion: 'R4B')]
class NarrativeLinkExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive $valueUrl = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/narrativeLink',
            value: $this->valueUrl,
        );
    }
}
