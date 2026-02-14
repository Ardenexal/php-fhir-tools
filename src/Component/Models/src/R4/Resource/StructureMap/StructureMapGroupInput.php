<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapInputModeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A name assigned to an instance of data. The instance must be provided when the mapping is invoked.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.input', fhirVersion: 'R4')]
class StructureMapGroupInput extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null name Name for this instance of data */
        #[NotBlank]
        public ?IdPrimitive $name = null,
        /** @var StringPrimitive|string|null type Type for this instance of data */
        public StringPrimitive|string|null $type = null,
        /** @var StructureMapInputModeType|null mode source | target */
        #[NotBlank]
        public ?StructureMapInputModeType $mode = null,
        /** @var StringPrimitive|string|null documentation Documentation for this instance of data */
        public StringPrimitive|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
