<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The parameters for the operation/query.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R5')]
class FHIROperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode name Name in Parameters.parameter.name or in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROperationParameterUseType $use = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROperationParameterScopeType> scope instance | type | system */
		public array $scope = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger min Minimum Cardinality */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string max Maximum Cardinality (a number or *) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown documentation Description of meaning/use */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRTypesType type What type this parameter has */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRTypesType> allowedType Allowed sub-type this parameter can have (if type is abstract) */
		public array $allowedType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> targetProfile If type is Reference | canonical, allowed targets. If type is 'Resource', then this constrains the allowed resource types */
		public array $targetProfile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchParamTypeType searchType number | date | string | token | reference | composite | quantity | uri | special */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchParamTypeType $searchType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROperationDefinitionParameterBinding binding ValueSet details if this is coded */
		public ?FHIROperationDefinitionParameterBinding $binding = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
		public array $referencedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROperationDefinitionParameter> part Parts of a nested Parameter */
		public array $part = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
