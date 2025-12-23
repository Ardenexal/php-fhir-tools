<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element OperationDefinition.parameter
 * @description The parameters for the operation/query.
 */
class FHIROperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode name Name in Parameters.parameter.name or in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationParameterUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger min Minimum Cardinality */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string max Maximum Cardinality (a number or *) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string documentation Description of meaning/use */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFHIRAllTypesType type What type this parameter has */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRFHIRAllTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> targetProfile If type is Reference | canonical, allowed targets */
		public array $targetProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSearchParamTypeType searchType number | date | string | token | reference | composite | quantity | uri | special */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSearchParamTypeType $searchType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameterBinding binding ValueSet details if this is coded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameterBinding $binding = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
		public array $referencedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameter> part Parts of a nested Parameter */
		public array $part = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
