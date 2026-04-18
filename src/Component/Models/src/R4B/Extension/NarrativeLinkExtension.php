<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/narrativeLink
 *
 * @description A human language representation of the concept (resource/element), as a url that is a reference to a portion of the narrative of a resource ([DomainResource.text](narrative.html) or [Composition.section.text](composition-definitions.html#Composition.section.text)).  To provide human language maintained separately from the narrative, use [originalText](StructureDefinition-originalText.html). To cross-link narrative and data, use the [textLink extension](StructureDefinition-textLink.html).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/narrativeLink', fhirVersion: 'R4B')]
class NarrativeLinkExtension extends Extension
{
    public function __construct(
        /** @var UrlPrimitive|null valueUrl Value of extension */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $valueUrl = null,
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
