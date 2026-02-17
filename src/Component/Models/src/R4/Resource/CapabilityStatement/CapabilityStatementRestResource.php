<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

/**
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource', fhirVersion: 'R4')]
class CapabilityStatementRestResource extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType type A resource type that is supported */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive profile Base System profile for all uses of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $profile = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> supportedProfile Profiles for use cases supported */
		public array $supportedProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive documentation Additional information about the use of the resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResourceInteraction> interaction What operations are supported? */
		public array $interaction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceVersionPolicyType versioning no-version | versioned | versioned-update */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceVersionPolicyType $versioning = null,
		/** @var null|bool readHistory Whether vRead can return past versions */
		public ?bool $readHistory = null,
		/** @var null|bool updateCreate If update can commit to a new identity */
		public ?bool $updateCreate = null,
		/** @var null|bool conditionalCreate If allows/uses conditional create */
		public ?bool $conditionalCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalReadStatusType conditionalRead not-supported | modified-since | not-match | full-support */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalReadStatusType $conditionalRead = null,
		/** @var null|bool conditionalUpdate If allows/uses conditional update */
		public ?bool $conditionalUpdate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalDeleteStatusType conditionalDelete not-supported | single | multiple - how conditional delete is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConditionalDeleteStatusType $conditionalDelete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
		public array $referencePolicy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> searchInclude _include values supported by the server */
		public array $searchInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> searchRevInclude _revinclude values supported by the server */
		public array $searchRevInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
		public array $searchParam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResourceOperation> operation Definition of a resource operation */
		public array $operation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
