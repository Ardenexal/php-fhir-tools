<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-inheritedExtensibleValueSet
 *
 * @description A reference to an extensible value set specified in a parent profie in order to allow a conformance checking tool to validate that a code not in the extensible value set of the profile is not violating rules defined by parent profile bindings.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-inheritedExtensibleValueSet', fhirVersion: 'R4')]
class InheritedExtensibleValueSetExtension extends Extension
{
    public function __construct(
        /** @var UriPrimitive|CanonicalPrimitive|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        UriPrimitive|CanonicalPrimitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-inheritedExtensibleValueSet',
            value: $value,
        );
    }
}
