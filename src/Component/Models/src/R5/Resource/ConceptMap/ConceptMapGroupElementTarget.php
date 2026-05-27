<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConceptMapRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A concept from the target value set that this concept maps to.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'cmd-1',
    severity: 'error',
    expression: 'comment.exists() or (%resource.status = \'draft\') or relationship.empty() or ((relationship != \'source-is-broader-than-target\') and (relationship != \'not-related-to\'))',
    human: 'If the map is source-is-broader-than-target or not-related-to, there SHALL be some comments, unless the status is \'draft\'',
)]
#[FHIRPathInvariant(
    key: 'cmd-7',
    severity: 'error',
    expression: '(code.exists() and valueSet.empty()) or (code.empty() and valueSet.exists())',
    human: 'Either code or valueSet SHALL be present but not both.',
)]
class ConceptMapGroupElementTarget extends BackboneElement
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
        /** @var CodePrimitive|null code Code that identifies the target element */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Display for the code */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $display = null,
        /** @var CanonicalPrimitive|null valueSet Identifies the set of target concepts */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ValueSet'])]
        public ?CanonicalPrimitive $valueSet = null,
        /** @var ConceptMapRelationshipType|null relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/concept-map-relationship|5.0.0', strength: 'required')]
        public ?ConceptMapRelationshipType $relationship = null,
        /** @var StringPrimitive|string|null comment Description of status/issues in mapping */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $comment = null,
        /** @var array<ConceptMapGroupElementTargetProperty> property Property value for the source -> target mapping */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMap\ConceptMapGroupElementTargetProperty',
        )]
        public array $property = [],
        /** @var array<ConceptMapGroupElementTargetDependsOn> dependsOn Other properties required for this mapping */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMap\ConceptMapGroupElementTargetDependsOn',
        )]
        public array $dependsOn = [],
        /** @var array<ConceptMapGroupElementTargetDependsOn> product Other data elements that this mapping also produces */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMap\ConceptMapGroupElementTargetDependsOn',
        )]
        public array $product = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
