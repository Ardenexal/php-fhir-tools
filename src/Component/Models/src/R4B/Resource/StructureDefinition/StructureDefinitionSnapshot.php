<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Symfony\Component\Validator\Constraints\Count;

/**
 * @description A snapshot view is expressed in a standalone form that can be used and interpreted without considering the base StructureDefinition.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.snapshot', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'sdf-3',
    severity: 'error',
    expression: 'element.all(definition.exists() and min.exists() and max.exists())',
    human: 'Each element definition in a snapshot must have a formal definition and cardinalities',
)]
#[FHIRPathInvariant(
    key: 'sdf-8',
    severity: 'error',
    expression: '(%resource.kind = \'logical\' or element.first().path = %resource.type) and element.tail().all(path.startsWith(%resource.snapshot.element.first().path&\'.\'))',
    human: 'All snapshot elements must start with the StructureDefinition\'s specified type for non-logical models, or with the same type name for logical models',
)]
#[FHIRPathInvariant(
    key: 'sdf-24',
    severity: 'error',
    expression: 'element.where(type.code=\'Reference\' and id.endsWith(\'.reference\') and type.targetProfile.exists() and id.substring(0,$this.length()-10) in %context.element.where(type.code=\'CodeableReference\').id).exists().not()',
    human: 'For CodeableReference elements, target profiles must be listed on the CodeableReference, not the CodeableReference.reference',
)]
#[FHIRPathInvariant(
    key: 'sdf-25',
    severity: 'error',
    expression: 'element.where(type.code=\'CodeableConcept\' and id.endsWith(\'.concept\') and binding.exists() and id.substring(0,$this.length()-8) in %context.element.where(type.code=\'CodeableReference\').id).exists().not()',
    human: 'For CodeableReference elements, bindings must be listed on the CodeableReference, not the CodeableReference.concept',
)]
#[FHIRPathInvariant(
    key: 'sdf-8b',
    severity: 'error',
    expression: 'element.all(base.exists())',
    human: 'All snapshot elements must have a base definition',
)]
class StructureDefinitionSnapshot extends BackboneElement
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
        /** @var array<ElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
        #[FhirProperty(
            fhirType: 'ElementDefinition',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ElementDefinition',
        )]
        #[Count(min: 1)]
        public array $element = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
