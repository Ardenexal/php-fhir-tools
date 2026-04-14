<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ClinicalUseDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @description Specifics for when this is an interaction.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.interaction', fhirVersion: 'R5')]
class ClinicalUseDefinitionInteraction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<ClinicalUseDefinitionInteractionInteractant> interactant The specific medication, product, food etc. or laboratory test that interacts */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClinicalUseDefinition\ClinicalUseDefinitionInteractionInteractant',
        )]
        public array $interactant = [],
        /** @var CodeableConcept|null type The type of the interaction e.g. drug-drug interaction, drug-lab test interaction */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var CodeableReference|null effect The effect of the interaction, for example "reduced gastric absorption of primary medication" */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $effect = null,
        /** @var CodeableConcept|null incidence The incidence of the interaction, e.g. theoretical, observed */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $incidence = null,
        /** @var array<CodeableConcept> management Actions for managing the interaction */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $management = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
