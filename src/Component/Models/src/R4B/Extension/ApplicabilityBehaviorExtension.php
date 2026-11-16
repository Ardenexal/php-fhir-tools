<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-applicabilityBehavior
 *
 * @description Determines whether child actions are evaluated independently (all) or evaluated in order with the first action that is applicable being performed (any).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-applicabilityBehavior', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.action')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.action.action')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup.action')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup.action.action')]
#[FHIRProfileBinding(path: 'value[x]', valueSetUrl: 'http://hl7.org/fhir/ValueSet/action-applicability-behavior', strength: 'required')]
class ApplicabilityBehaviorExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-applicabilityBehavior',
            value: $this->valueCode,
        );
    }
}
