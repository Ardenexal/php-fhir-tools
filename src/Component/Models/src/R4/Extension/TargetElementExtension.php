<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/targetElement
 *
 * @description Indicates that the reference has a particular focus in the target resource, a particular element of interest, identified by its element id. This extension requires that an 'id' property be present on the element to be referenced, but does not require the use of simplified FHIRPath.  If the creator of the reference can't ensure an id will be present, the [[[http://hl7.org/fhir/StructureDefinition/targetPath]]] extension can be used instead.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/targetElement', fhirVersion: 'R4')]
class TargetElementExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|null valueUri Value of extension */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $valueUri = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/targetElement',
            value: $this->valueUri,
        );
    }
}
