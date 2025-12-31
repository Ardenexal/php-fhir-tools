<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ParameterDefinition
 * @description The parameters to the module. This collection specifies both the input and output parameters. Input parameters are provided by the caller as part of the $evaluate operation. Output parameters are included in the GuidanceResponse.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ParameterDefinition', fhirVersion: 'R4')]
class FHIRParameterDefinition extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode name Name used to access the parameter value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIROperationParameterUseType use in | out */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIROperationParameterUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger min Minimum cardinality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string max Maximum cardinality (a number of *) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string documentation A brief description of the parameter */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFHIRAllTypesType type What type of value */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFHIRAllTypesType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical profile What profile the value is expected to be */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical $profile = null,
	) {
		parent::__construct($id, $extension);
	}
}
