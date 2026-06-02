<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/replaces
 *
 * @description This indicates a separate conformance resource instance that is superseded by the current instance.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/replaces', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'ActorDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'ChargeItemDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'CompartmentDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ExampleScenario')]
#[FHIRExtensionContext(type: 'element', expression: 'GraphDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide')]
#[FHIRExtensionContext(type: 'element', expression: 'MessageDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire')]
#[FHIRExtensionContext(type: 'element', expression: 'Requirements')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureMap')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic')]
#[FHIRExtensionContext(type: 'element', expression: 'TerminologyCapabilities')]
#[FHIRExtensionContext(type: 'element', expression: 'TestScript')]
class ReplacesExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/replaces',
            value: $this->valueCanonical,
        );
    }
}
