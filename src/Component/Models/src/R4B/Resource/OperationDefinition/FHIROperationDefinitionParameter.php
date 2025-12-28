<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The parameters for the operation/query.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter', fhirVersion: 'R4B')]
class FHIROperationDefinitionParameter extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        public ?\FHIRCode $name = null,
        /** @var FHIROperationParameterUseType|null use in | out */
        #[NotBlank]
        public ?\FHIROperationParameterUseType $use = null,
        /** @var FHIRInteger|null min Minimum Cardinality */
        #[NotBlank]
        public ?\FHIRInteger $min = null,
        /** @var FHIRString|string|null max Maximum Cardinality (a number or *) */
        #[NotBlank]
        public \FHIRString|string|null $max = null,
        /** @var FHIRString|string|null documentation Description of meaning/use */
        public \FHIRString|string|null $documentation = null,
        /** @var FHIRFHIRAllTypesType|null type What type this parameter has */
        public ?\FHIRFHIRAllTypesType $type = null,
        /** @var array<FHIRCanonical> targetProfile If type is Reference | canonical, allowed targets */
        public array $targetProfile = [],
        /** @var FHIRSearchParamTypeType|null searchType number | date | string | token | reference | composite | quantity | uri | special */
        public ?\FHIRSearchParamTypeType $searchType = null,
        /** @var FHIROperationDefinitionParameterBinding|null binding ValueSet details if this is coded */
        public ?\FHIROperationDefinitionParameterBinding $binding = null,
        /** @var array<FHIROperationDefinitionParameterReferencedFrom> referencedFrom References to this parameter */
        public array $referencedFrom = [],
        /** @var array<FHIROperationDefinitionParameter> part Parts of a nested Parameter */
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
