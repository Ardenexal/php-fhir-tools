<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ParameterDefinition
 * @description The parameters to the module. This collection specifies both the input and output parameters. Input parameters are provided by the caller as part of the $evaluate operation. Output parameters are included in the GuidanceResponse.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ParameterDefinition', fhirVersion: 'R4')]
class ParameterDefinition extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive name Name used to access the parameter value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?OperationParameterUseType $use = null,
		/** @var null|int min Minimum cardinality */
		public ?int $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string max Maximum cardinality (a number of *) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string documentation A brief description of the parameter */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType type What type of value */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAllTypesType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive profile What profile the value is expected to be */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $profile = null,
	) {
		parent::__construct($id, $extension);
	}
}
