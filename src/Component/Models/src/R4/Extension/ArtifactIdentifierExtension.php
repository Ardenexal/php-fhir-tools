<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-identifier
 *
 * @description A formal identifier that is used to identify this artifact when it is represented in other formats, or referenced in a specification, model, design or an instance.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-identifier', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalUseDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'CompartmentDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'GraphDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationKnowledge')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'SubstanceDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SubscriptionTopic')]
#[FHIRExtensionContext(type: 'element', expression: 'TerminologyCapabilities')]
class ArtifactIdentifierExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var Identifier|null valueIdentifier Value of extension */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $valueIdentifier = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-identifier',
            value: $this->valueIdentifier,
        );
    }
}
