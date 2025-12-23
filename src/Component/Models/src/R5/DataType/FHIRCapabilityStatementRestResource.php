<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.rest.resource
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
class FHIRCapabilityStatementRestResource extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceTypeType type A resource type that is supported */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical profile System-wide profile */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $profile = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> supportedProfile Use-case specific profiles */
		public array $supportedProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown documentation Additional information about the use of the resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResourceInteraction> interaction What operations are supported? */
		public array $interaction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceVersionPolicyType versioning no-version | versioned | versioned-update */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceVersionPolicyType $versioning = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean readHistory Whether vRead can return past versions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $readHistory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean updateCreate If update can commit to a new identity */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $updateCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean conditionalCreate If allows/uses conditional create */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $conditionalCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConditionalReadStatusType conditionalRead not-supported | modified-since | not-match | full-support */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConditionalReadStatusType $conditionalRead = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean conditionalUpdate If allows/uses conditional update */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $conditionalUpdate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean conditionalPatch If allows/uses conditional patch */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $conditionalPatch = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConditionalDeleteStatusType conditionalDelete not-supported | single | multiple - how conditional delete is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConditionalDeleteStatusType $conditionalDelete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
		public array $referencePolicy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> searchInclude _include values supported by the server */
		public array $searchInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> searchRevInclude _revinclude values supported by the server */
		public array $searchRevInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
		public array $searchParam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResourceOperation> operation Definition of a resource operation */
		public array $operation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
