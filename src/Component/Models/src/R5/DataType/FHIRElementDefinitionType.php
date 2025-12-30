<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The data type or resource that the value of this element is permitted to be.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.type', fhirVersion: 'R5')]
class FHIRElementDefinitionType extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRUri|null code Data type or Resource (reference to definition) */
        #[NotBlank]
        public ?FHIRUri $code = null,
        /** @var array<FHIRCanonical> profile Profiles (StructureDefinition or IG) - one must apply */
        public array $profile = [],
        /** @var array<FHIRCanonical> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
        public array $targetProfile = [],
        /** @var array<FHIRAggregationModeType> aggregation contained | referenced | bundled - how aggregated */
        public array $aggregation = [],
        /** @var FHIRReferenceVersionRulesType|null versioning either | independent | specific */
        public ?FHIRReferenceVersionRulesType $versioning = null,
    ) {
        parent::__construct($id, $extension);
    }
}
