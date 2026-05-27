<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-cqlType
 *
 * @description Surfaces the CQL type of the extension, parameter, or parameter definition on which it appears.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-cqlType', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ParameterDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Parameters.parameter')]
#[FHIRExtensionContext(type: 'element', expression: 'Parameters.parameter.part')]
#[FHIRExtensionContext(type: 'element', expression: 'Extension')]
class CqlTypeExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-cqlType',
            value: $this->valueString,
        );
    }
}
