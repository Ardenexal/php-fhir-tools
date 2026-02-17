<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The data type or resource that the value of this element is permitted to be.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.type', fhirVersion: 'R4')]
class ElementDefinitionType extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var UriPrimitive|null code Data type or Resource (reference to definition) */
        #[NotBlank]
        public ?UriPrimitive $code = null,
        /** @var array<CanonicalPrimitive> profile Profiles (StructureDefinition or IG) - one must apply */
        public array $profile = [],
        /** @var array<CanonicalPrimitive> targetProfile Profile (StructureDefinition or IG) on the Reference/canonical target - one must apply */
        public array $targetProfile = [],
        /** @var array<AggregationModeType> aggregation contained | referenced | bundled - how aggregated */
        public array $aggregation = [],
        /** @var ReferenceVersionRulesType|null versioning either | independent | specific */
        public ?ReferenceVersionRulesType $versioning = null,
    ) {
        parent::__construct($id, $extension);
    }
}
