<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-category
 *
 * @description The category under which the resource type is presented on the official resource list.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-category', fhirVersion: 'R4B')]
class CategoryExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-category',
            value: $this->valueString,
        );
    }
}
