<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An external specification that the content is mapped to.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.mapping', fhirVersion: 'R4')]
class FHIRStructureDefinitionMapping extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null identity Internal id when this mapping is used */
        #[NotBlank]
        public ?FHIRId $identity = null,
        /** @var FHIRUri|null uri Identifies what this mapping refers to */
        public ?FHIRUri $uri = null,
        /** @var FHIRString|string|null name Names what this mapping refers to */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null comment Versions, Issues, Scope limitations etc. */
        public FHIRString|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
