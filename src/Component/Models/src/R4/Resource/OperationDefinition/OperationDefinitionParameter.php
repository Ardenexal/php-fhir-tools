<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationParameterUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The parameters for the operation/query.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R4')]
#[FHIRPathInvariant(
    key: 'opd-1',
    severity: 'error',
    expression: 'type.exists() or part.exists()',
    human: 'Either a type must be provided, or parts',
)]
#[FHIRPathInvariant(
    key: 'opd-2',
    severity: 'error',
    expression: 'searchType.exists() implies type = \'string\'',
    human: 'A search type can only be specified for parameters of type string',
)]
#[FHIRPathInvariant(
    key: 'opd-3',
    severity: 'error',
    expression: 'targetProfile.exists() implies (type = \'Reference\' or type = \'canonical\')',
    human: 'A targetProfile can only be specified for parameters of type Reference or Canonical',
)]
class OperationDefinitionParameter extends BackboneElement
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
        /** @var CodePrimitive|null name Name in Parameters.parameter.name or in URL */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CodePrimitive $name = null,
        /** @var OperationParameterUseType|null use in | out */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/operation-parameter-use|4.0.1', strength: 'required')]
        public ?OperationParameterUseType $use = null,
        /** @var int|null min Minimum Cardinality */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?int $min = null,
        /** @var StringPrimitive|string|null max Maximum Cardinality (a number or *) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $max = null,
        /** @var StringPrimitive|string|null documentation Description of meaning/use */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $documentation = null,
        /** @var FHIRAllTypesType|null type What type this parameter has */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-types|4.0.1', strength: 'required')]
        public ?FHIRAllTypesType $type = null,
        /** @var array<CanonicalPrimitive> targetProfile If type is Reference | canonical, allowed targets */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/StructureDefinition'])]
        public array $targetProfile = [],
        /** @var SearchParamTypeType|null searchType number | date | string | token | reference | composite | quantity | uri | special */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-param-type|4.0.1', strength: 'required')]
        public ?SearchParamTypeType $searchType = null,
        /** @var OperationDefinitionParameterBinding|null binding ValueSet details if this is coded */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?OperationDefinitionParameterBinding $binding = null,
        /** @var array<OperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameterReferencedFrom',
        )]
        public array $referencedFrom = [],
        /** @var array<OperationDefinitionParameter> part Parts of a nested Parameter */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameter',
        )]
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
