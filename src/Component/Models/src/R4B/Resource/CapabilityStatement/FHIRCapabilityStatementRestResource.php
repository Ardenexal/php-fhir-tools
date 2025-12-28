<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource', fhirVersion: 'R4B')]
class FHIRCapabilityStatementRestResource extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceTypeType type A resource type that is supported */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical profile Base System profile for all uses of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical $profile = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical> supportedProfile Profiles for use cases supported */
		public array $supportedProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown documentation Additional information about the use of the resource type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCapabilityStatementRestResourceInteraction> interaction What operations are supported? */
		public array $interaction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceVersionPolicyType versioning no-version | versioned | versioned-update */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceVersionPolicyType $versioning = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean readHistory Whether vRead can return past versions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $readHistory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean updateCreate If update can commit to a new identity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $updateCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean conditionalCreate If allows/uses conditional create */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $conditionalCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalReadStatusType conditionalRead not-supported | modified-since | not-match | full-support */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalReadStatusType $conditionalRead = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean conditionalUpdate If allows/uses conditional update */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $conditionalUpdate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalDeleteStatusType conditionalDelete not-supported | single | multiple - how conditional delete is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalDeleteStatusType $conditionalDelete = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
		public array $referencePolicy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> searchInclude _include values supported by the server */
		public array $searchInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string> searchRevInclude _revinclude values supported by the server */
		public array $searchRevInclude = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
		public array $searchParam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCapabilityStatementRestResourceOperation> operation Definition of a resource operation */
		public array $operation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
