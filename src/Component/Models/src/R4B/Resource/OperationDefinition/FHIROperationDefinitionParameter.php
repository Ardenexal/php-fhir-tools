<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The parameters for the operation/query.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R4B')]
class FHIROperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode name Name in Parameters.parameter.name or in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIROperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIROperationParameterUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger min Minimum Cardinality */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string max Maximum Cardinality (a number or *) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string documentation Description of meaning/use */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFHIRAllTypesType type What type this parameter has */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFHIRAllTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical> targetProfile If type is Reference | canonical, allowed targets */
		public array $targetProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchParamTypeType searchType number | date | string | token | reference | composite | quantity | uri | special */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchParamTypeType $searchType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameterBinding binding ValueSet details if this is coded */
		public ?FHIROperationDefinitionParameterBinding $binding = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
		public array $referencedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIROperationDefinitionParameter> part Parts of a nested Parameter */
		public array $part = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
