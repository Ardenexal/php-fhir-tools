<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRTypesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROperationParameterScopeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIROperationParameterUseType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The parameters for the operation/query.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R5')]
class FHIROperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null name Name in Parameters.parameter.name or in URL */
        #[NotBlank]
        public ?FHIRCode $name = null,
        /** @var FHIROperationParameterUseType|null use in | out */
        #[NotBlank]
        public ?FHIROperationParameterUseType $use = null,
        /** @var array<FHIROperationParameterScopeType> scope instance | type | system */
        public array $scope = [],
        /** @var FHIRInteger|null min Minimum Cardinality */
        #[NotBlank]
        public ?FHIRInteger $min = null,
        /** @var FHIRString|string|null max Maximum Cardinality (a number or *) */
        #[NotBlank]
        public FHIRString|string|null $max = null,
        /** @var FHIRMarkdown|null documentation Description of meaning/use */
        public ?FHIRMarkdown $documentation = null,
        /** @var FHIRFHIRTypesType|null type What type this parameter has */
        public ?FHIRFHIRTypesType $type = null,
        /** @var array<FHIRFHIRTypesType> allowedType Allowed sub-type this parameter can have (if type is abstract) */
        public array $allowedType = [],
        /** @var array<FHIRCanonical> targetProfile If type is Reference | canonical, allowed targets. If type is 'Resource', then this constrains the allowed resource types */
        public array $targetProfile = [],
        /** @var FHIRSearchParamTypeType|null searchType number | date | string | token | reference | composite | quantity | uri | special */
        public ?FHIRSearchParamTypeType $searchType = null,
        /** @var FHIROperationDefinitionParameterBinding|null binding ValueSet details if this is coded */
        public ?FHIROperationDefinitionParameterBinding $binding = null,
        /** @var array<FHIROperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
        public array $referencedFrom = [],
        /** @var array<FHIROperationDefinitionParameter> part Parts of a nested Parameter */
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
