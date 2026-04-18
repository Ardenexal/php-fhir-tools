<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-directReferenceCode
 *
 * @description Indicates that the given code is directly referenced by artifact logic (e.g. as a [direct-reference code](https://cql.hl7.org/02-authorsguide.html#code) in CQL). Terminology dependencies used in logic can be collected and reported, typically using the relatedArtifact element with a type of `depends-on`. However, direct-reference codes cannot be represented in the relatedArtifact element, so this extension provides a means to support communicating direct-reference code dependencies of knowledge artifacts. As with value set dependencies, direct-reference codes may be the terminology target of a data requirement.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-directReferenceCode', fhirVersion: 'R4B')]
class DirectReferenceCodeExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding Value of extension */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $valueCoding = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-directReferenceCode',
            value: $this->valueCoding,
        );
    }
}
