<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ParameterDefinition
 *
 * @description The parameters to the module. This collection specifies both the input and output parameters. Input parameters are provided by the caller as part of the $evaluate operation. Output parameters are included in the GuidanceResponse.
 */
#[FHIRComplexType(typeName: 'ParameterDefinition', fhirVersion: 'R4')]
class ParameterDefinition extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var CodePrimitive|null name Name used to access the parameter value */
        public ?CodePrimitive $name = null,
        /** @var OperationParameterUseType|null use in | out */
        #[NotBlank]
        public ?OperationParameterUseType $use = null,
        /** @var int|null min Minimum cardinality */
        public ?int $min = null,
        /** @var StringPrimitive|string|null max Maximum cardinality (a number of *) */
        public StringPrimitive|string|null $max = null,
        /** @var StringPrimitive|string|null documentation A brief description of the parameter */
        public StringPrimitive|string|null $documentation = null,
        /** @var FHIRAllTypesType|null type What type of value */
        #[NotBlank]
        public ?FHIRAllTypesType $type = null,
        /** @var CanonicalPrimitive|null profile What profile the value is expected to be */
        public ?CanonicalPrimitive $profile = null,
    ) {
        parent::__construct($id, $extension);
    }
}
