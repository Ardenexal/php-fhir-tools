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
 * @see http://hl7.org/fhir/StructureDefinition/alternate-canonical
 *
 * @description Used with inter-version extensions where the element being referenced by inter-version extension is of type 'canonical' and includes a reference to a resource whose canonical URL is different in the referencing version than it is in the FHIR version where the element was defined.  E.g. if an R5 implementation were using inter-version extensions to support an element that referenced Bar, when in R7, the url would have been .../Foo.  In this situation, the canonical element would have no value and would instead have an extension that referred to the canonical URL of the '../Bar' resource (which would technically not be supported in R7, but is appropriate in R5).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/alternate-canonical', fhirVersion: 'R4B')]
class AlternateCanonicalExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/alternate-canonical',
            value: $this->valueUrl,
        );
    }
}
