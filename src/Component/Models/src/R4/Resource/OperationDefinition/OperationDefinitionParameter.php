<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationParameterUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The parameters for the operation/query.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R4')]
class OperationDefinitionParameter extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null name Name in Parameters.parameter.name or in URL */
        #[NotBlank]
        public ?CodePrimitive $name = null,
        /** @var OperationParameterUseType|null use in | out */
        #[NotBlank]
        public ?OperationParameterUseType $use = null,
        /** @var int|null min Minimum Cardinality */
        #[NotBlank]
        public ?int $min = null,
        /** @var StringPrimitive|string|null max Maximum Cardinality (a number or *) */
        #[NotBlank]
        public StringPrimitive|string|null $max = null,
        /** @var StringPrimitive|string|null documentation Description of meaning/use */
        public StringPrimitive|string|null $documentation = null,
        /** @var FHIRAllTypesType|null type What type this parameter has */
        public ?FHIRAllTypesType $type = null,
        /** @var array<CanonicalPrimitive> targetProfile If type is Reference | canonical, allowed targets */
        public array $targetProfile = [],
        /** @var SearchParamTypeType|null searchType number | date | string | token | reference | composite | quantity | uri | special */
        public ?SearchParamTypeType $searchType = null,
        /** @var OperationDefinitionParameterBinding|null binding ValueSet details if this is coded */
        public ?OperationDefinitionParameterBinding $binding = null,
        /** @var array<OperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
        public array $referencedFrom = [],
        /** @var array<OperationDefinitionParameter> part Parts of a nested Parameter */
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
