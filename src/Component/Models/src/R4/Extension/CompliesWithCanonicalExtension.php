<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/complies-with-canonical
 *
 * @description Indicates that a resource or element complies with another formally defined canonical resource. This extension allows systems to declare that their implementation satisfies multiple canonical definitions beyond the primary one referenced in corresponding 'definition' elements.
 *
 * Compliance means that an implementation of a canonical resource (e.g., a search parameter or operation) satisfies all the normative requirements of the referenced canonical resource definition. When an implementation states that it complies with multiple canonical definitions, it means that it simultaneously satisfies all mandatory requirements from each referenced definition. This enables a single implementation to declare conformance to multiple sources without having to duplicate capability statement entries.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/complies-with-canonical', fhirVersion: 'R4')]
class CompliesWithCanonicalExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/complies-with-canonical',
            value: $this->valueCanonical,
        );
    }
}
