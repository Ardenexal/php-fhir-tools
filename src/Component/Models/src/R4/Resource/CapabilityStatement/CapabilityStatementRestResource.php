<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalDeleteStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalReadStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ReferenceHandlingPolicyType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceVersionPolicyType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource', fhirVersion: 'R4')]
class CapabilityStatementRestResource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ResourceTypeType|null type A resource type that is supported */
        #[NotBlank]
        public ?ResourceTypeType $type = null,
        /** @var CanonicalPrimitive|null profile Base System profile for all uses of resource */
        public ?CanonicalPrimitive $profile = null,
        /** @var array<CanonicalPrimitive> supportedProfile Profiles for use cases supported */
        public array $supportedProfile = [],
        /** @var MarkdownPrimitive|null documentation Additional information about the use of the resource type */
        public ?MarkdownPrimitive $documentation = null,
        /** @var array<CapabilityStatementRestResourceInteraction> interaction What operations are supported? */
        public array $interaction = [],
        /** @var ResourceVersionPolicyType|null versioning no-version | versioned | versioned-update */
        public ?ResourceVersionPolicyType $versioning = null,
        /** @var bool|null readHistory Whether vRead can return past versions */
        public ?bool $readHistory = null,
        /** @var bool|null updateCreate If update can commit to a new identity */
        public ?bool $updateCreate = null,
        /** @var bool|null conditionalCreate If allows/uses conditional create */
        public ?bool $conditionalCreate = null,
        /** @var ConditionalReadStatusType|null conditionalRead not-supported | modified-since | not-match | full-support */
        public ?ConditionalReadStatusType $conditionalRead = null,
        /** @var bool|null conditionalUpdate If allows/uses conditional update */
        public ?bool $conditionalUpdate = null,
        /** @var ConditionalDeleteStatusType|null conditionalDelete not-supported | single | multiple - how conditional delete is supported */
        public ?ConditionalDeleteStatusType $conditionalDelete = null,
        /** @var array<ReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
        public array $referencePolicy = [],
        /** @var array<StringPrimitive|string> searchInclude _include values supported by the server */
        public array $searchInclude = [],
        /** @var array<StringPrimitive|string> searchRevInclude _revinclude values supported by the server */
        public array $searchRevInclude = [],
        /** @var array<CapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
        public array $searchParam = [],
        /** @var array<CapabilityStatementRestResourceOperation> operation Definition of a resource operation */
        public array $operation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
