<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-codegen-super
 *
 * @description A specific instruction to use an intermediate parent class when generating code for the classes.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-codegen-super', fhirVersion: 'R4')]
class CodegenSuperExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-codegen-super',
            value: $this->valueString,
        );
    }
}
