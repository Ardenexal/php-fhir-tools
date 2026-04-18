<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConditionalDeleteStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ConditionalReadStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ReferenceHandlingPolicyType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ResourceVersionPolicyType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource', fhirVersion: 'R5')]
class CapabilityStatementRestResource extends BackboneElement
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
        /** @var ResourceTypeType|null type A resource type that is supported */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ResourceTypeType $type = null,
        /** @var CanonicalPrimitive|null profile System-wide profile */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $profile = null,
        /** @var array<CanonicalPrimitive> supportedProfile Use-case specific profiles */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $supportedProfile = [],
        /** @var MarkdownPrimitive|null documentation Additional information about the use of the resource type */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        /** @var array<CapabilityStatementRestResourceInteraction> interaction What operations are supported? */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatement\CapabilityStatementRestResourceInteraction',
        )]
        public array $interaction = [],
        /** @var ResourceVersionPolicyType|null versioning no-version | versioned | versioned-update */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ResourceVersionPolicyType $versioning = null,
        /** @var bool|null readHistory Whether vRead can return past versions */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $readHistory = null,
        /** @var bool|null updateCreate If update can commit to a new identity */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $updateCreate = null,
        /** @var bool|null conditionalCreate If allows/uses conditional create */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $conditionalCreate = null,
        /** @var ConditionalReadStatusType|null conditionalRead not-supported | modified-since | not-match | full-support */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ConditionalReadStatusType $conditionalRead = null,
        /** @var bool|null conditionalUpdate If allows/uses conditional update */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $conditionalUpdate = null,
        /** @var bool|null conditionalPatch If allows/uses conditional patch */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $conditionalPatch = null,
        /** @var ConditionalDeleteStatusType|null conditionalDelete not-supported | single | multiple - how conditional delete is supported */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ConditionalDeleteStatusType $conditionalDelete = null,
        /** @var array<ReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $referencePolicy = [],
        /** @var array<StringPrimitive|string> searchInclude _include values supported by the server */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $searchInclude = [],
        /** @var array<StringPrimitive|string> searchRevInclude _revinclude values supported by the server */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $searchRevInclude = [],
        /** @var array<CapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatement\CapabilityStatementRestResourceSearchParam',
        )]
        public array $searchParam = [],
        /** @var array<CapabilityStatementRestResourceOperation> operation Definition of a resource operation */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\CapabilityStatement\CapabilityStatementRestResourceOperation',
        )]
        public array $operation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
