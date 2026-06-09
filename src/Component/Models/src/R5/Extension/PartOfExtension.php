<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / Clinical Quality Information
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-partOf
 *
 * @description Specifies an overall specification artifact that this manifest or release is part of.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-partOf', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Library')]
class PartOfExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-partOf',
            value: $this->valueCanonical,
        );
    }
}
