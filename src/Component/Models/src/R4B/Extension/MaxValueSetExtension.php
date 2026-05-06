<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet
 *
 * @description This acts as an overall 'required' binding for an element that already has a less restrictive binding.  It is used when there is a desire to have a 'small' enumerable binding that meets most needs and where the overall value set that the codes must be drawn from is 'infinite'.  The the base less restrictive binding SHALL be a proper subset of the max binding valueset.  In most cases, this extension is used where the base binding is 'extensible'.  DEPRECATED: Use additionalBinding extension or element instead
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet', fhirVersion: 'R4B')]
class MaxValueSetExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-maxValueSet',
            value: $value,
        );
    }
}
