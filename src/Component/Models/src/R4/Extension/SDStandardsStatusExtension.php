<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-standards-status
 *
 * @description The Current HL7 ballot/Standards status of this artifact.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-standards-status', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'CanonicalResource')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem.concept')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition.context')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.base')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.type')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.type.targetProfile')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition.parameter')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition.parameter.type')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition.parameter.allowedType')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition.parameter.targetProfile')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide.definition.page')]
class SDStandardsStatusExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-standards-status',
            value: $this->valueCode,
        );
    }
}
