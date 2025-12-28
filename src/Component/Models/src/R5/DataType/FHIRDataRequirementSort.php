<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSortDirectionType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specifies the order of the results to be returned.
 */
#[FHIRComplexType(typeName: 'DataRequirement.sort', fhirVersion: 'R5')]
class FHIRDataRequirementSort extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null path The name of the attribute to perform the sort */
        #[NotBlank]
        public FHIRString|string|null $path = null,
        /** @var FHIRSortDirectionType|null direction ascending | descending */
        #[NotBlank]
        public ?FHIRSortDirectionType $direction = null,
    ) {
        parent::__construct($id, $extension);
    }
}
