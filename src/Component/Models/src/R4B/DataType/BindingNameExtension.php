<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-bindingName
 *
 * @description A name that can be used for code generation when generating named enumerations for the binding.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bindingName', fhirVersion: 'R4B')]
class BindingNameExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bindingName',
            value: $this->valueString,
        );
    }
}
