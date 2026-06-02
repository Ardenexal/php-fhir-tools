<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConceptMapGroupUnmappedModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConceptMapRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What to do when there is no mapping to a target concept from the source concept and ConceptMap.group.element.noMap is not true. This provides the "default" to be applied when there is no target concept mapping specified or the expansion of ConceptMap.group.element.target.valueSet is empty.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.unmapped', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'cmd-2',
    severity: 'error',
    expression: '(mode = \'fixed\') implies ((code.exists() and valueSet.empty()) or (code.empty() and valueSet.exists()))',
    human: 'If the mode is \'fixed\', either a code or valueSet must be provided, but not both.',
)]
#[FHIRPathInvariant(
    key: 'cmd-3',
    severity: 'error',
    expression: '(mode = \'other-map\') implies otherMap.exists()',
    human: 'If the mode is \'other-map\', a url for the other map must be provided',
)]
#[FHIRPathInvariant(
    key: 'cmd-8',
    severity: 'error',
    expression: '(mode != \'fixed\') implies (code.empty() and display.empty() and valueSet.empty())',
    human: 'If the mode is not \'fixed\', code, display and valueSet are not allowed',
)]
#[FHIRPathInvariant(
    key: 'cmd-9',
    severity: 'error',
    expression: '(mode != \'other-map\') implies relationship.exists()',
    human: 'If the mode is not \'other-map\', relationship must be provided',
)]
#[FHIRPathInvariant(
    key: 'cmd-10',
    severity: 'error',
    expression: '(mode != \'other-map\') implies otherMap.empty()',
    human: 'If the mode is not \'other-map\', otherMap is not allowed',
)]
class ConceptMapGroupUnmapped extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var ConceptMapGroupUnmappedModeType|null mode use-source-code | fixed | other-map */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode|5.0.0', strength: 'required')]
        public ?ConceptMapGroupUnmappedModeType $mode = null,
        /** @var CodePrimitive|null code Fixed code when mode = fixed */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Display for the code */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $display = null,
        /** @var CanonicalPrimitive|null valueSet Fixed code set when mode = fixed */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ValueSet'])]
        public ?CanonicalPrimitive $valueSet = null,
        /** @var ConceptMapRelationshipType|null relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/concept-map-relationship|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'The \'not-related-to\' relationship means that there is no mapping from the source to the target, and the mapping cannot be interpreted without knowing this value as it could mean the elements are equivalent, totally mismatched or anything in between')]
        public ?ConceptMapRelationshipType $relationship = null,
        /** @var CanonicalPrimitive|null otherMap canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ConceptMap'])]
        public ?CanonicalPrimitive $otherMap = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
