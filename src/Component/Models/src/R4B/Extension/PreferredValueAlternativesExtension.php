<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/preferredValueAlternatives
 *
 * @description An extension (or if multiple, a collection of alternative extensions) that SHOULD be used in the event a value element is not present.
 *
 * This is similar to valueAlternatives, however, unlike valueAlternatives it is not an error if extensions other than those listed in the 'preferredValueAlternatives' are used in place of a value.  Like valueAlternatives, this extension is only appropriate on primitive types.  It makes no sense if specified on an element where mustHaveValue is true.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/preferredValueAlternatives', fhirVersion: 'R4B')]
class PreferredValueAlternativesExtension extends Extension
{
    public function __construct(
        /** @var CanonicalPrimitive|null valueCanonical Value of extension */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $valueCanonical = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/preferredValueAlternatives',
            value: $this->valueCanonical,
        );
    }
}
