<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRFHIRAllTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIROperationParameterUseType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ParameterDefinition
 *
 * @description The parameters to the module. This collection specifies both the input and output parameters. Input parameters are provided by the caller as part of the $evaluate operation. Output parameters are included in the GuidanceResponse.
 */
#[FHIRComplexType(typeName: 'ParameterDefinition', fhirVersion: 'R4')]
class FHIRParameterDefinition extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCode|null name Name used to access the parameter value */
        public ?FHIRCode $name = null,
        /** @var FHIROperationParameterUseType|null use in | out */
        #[NotBlank]
        public ?FHIROperationParameterUseType $use = null,
        /** @var FHIRInteger|null min Minimum cardinality */
        public ?FHIRInteger $min = null,
        /** @var FHIRString|string|null max Maximum cardinality (a number of *) */
        public FHIRString|string|null $max = null,
        /** @var FHIRString|string|null documentation A brief description of the parameter */
        public FHIRString|string|null $documentation = null,
        /** @var FHIRFHIRAllTypesType|null type What type of value */
        #[NotBlank]
        public ?FHIRFHIRAllTypesType $type = null,
        /** @var FHIRCanonical|null profile What profile the value is expected to be */
        public ?FHIRCanonical $profile = null,
    ) {
        parent::__construct($id, $extension);
    }
}
