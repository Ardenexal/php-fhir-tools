<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith
 *
 * @description In satisfying this request or instantiating this definition, the expectations defined in the Definition resource are expected to be met.  (This allows requirements defined elsewhere to be brought into play by reference rather than providing all of the detail in-line necessary to satisfy the referenced Definition.).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith', fhirVersion: 'R4B')]
class ShallComplyWithExtension extends Extension
{
    public function __construct(
        /** @var CanonicalPrimitive|Reference|UriPrimitive|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        CanonicalPrimitive|Reference|UriPrimitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-shallComplyWith',
            value: $value,
        );
    }
}
