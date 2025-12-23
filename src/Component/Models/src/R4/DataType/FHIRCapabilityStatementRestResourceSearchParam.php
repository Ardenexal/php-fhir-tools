<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.rest.resource.searchParam
 * @description Search parameters for implementations to support and/or make use of - either references to ones defined in the specification, or additional ones defined for/by the implementation.
 */
class FHIRCapabilityStatementRestResourceSearchParam extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Name of search parameter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical definition Source of definition for parameter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical $definition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchParamTypeType type number | date | string | token | reference | composite | quantity | uri | special */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchParamTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown documentation Server-specific usage */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $documentation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
