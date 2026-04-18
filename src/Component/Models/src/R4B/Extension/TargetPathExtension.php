<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/targetPath
 *
 * @description Indicates that the reference has a  particular focus in the target resource, a particular element of interest, identified by a FHIRPath statement. The FHIRPath expression is limited to a the [simple subset](fhirpath.html#simple) with the additional limitation that .resolve() that is not allowed. This is a more sophisticated mechanism of referring to an element than [[[http://hl7.org/fhir/StructureDefinition/targetElement]]] but does require the system resolving the item to be able to use at least FHIRPath.  If the author of the reference has the ability to ensure an id will be present on the target [[[http://hl7.org/fhir/StructureDefinition/targetElement]]] might be more widely useable.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/targetPath', fhirVersion: 'R4B')]
class TargetPathExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/targetPath',
            value: $this->valueString,
        );
    }
}
