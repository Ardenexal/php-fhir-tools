<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A name assigned to an instance of data. The instance must be provided when the mapping is invoked.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.input', fhirVersion: 'R4B')]
class FHIRStructureMapGroupInput extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null name Name for this instance of data */
        #[NotBlank]
        public ?\FHIRId $name = null,
        /** @var FHIRString|string|null type Type for this instance of data */
        public \FHIRString|string|null $type = null,
        /** @var FHIRStructureMapInputModeType|null mode source | target */
        #[NotBlank]
        public ?\FHIRStructureMapInputModeType $mode = null,
        /** @var FHIRString|string|null documentation Documentation for this instance of data */
        public \FHIRString|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
