<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition;

/**
 * @description The parameters for the operation/query.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R4')]
class OperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive name Name in Parameters.parameter.name or in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationParameterUseType $use = null,
		/** @var null|int min Minimum Cardinality */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?int $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string max Maximum Cardinality (a number or *) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string documentation Description of meaning/use */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType type What type this parameter has */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> targetProfile If type is Reference | canonical, allowed targets */
		public array $targetProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType searchType number | date | string | token | reference | composite | quantity | uri | special */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType $searchType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameterBinding binding ValueSet details if this is coded */
		public ?OperationDefinitionParameterBinding $binding = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
		public array $referencedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameter> part Parts of a nested Parameter */
		public array $part = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
