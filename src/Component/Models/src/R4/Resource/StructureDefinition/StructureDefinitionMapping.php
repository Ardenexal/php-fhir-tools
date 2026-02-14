<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An external specification that the content is mapped to.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.mapping', fhirVersion: 'R4')]
class StructureDefinitionMapping extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null identity Internal id when this mapping is used */
        #[NotBlank]
        public ?IdPrimitive $identity = null,
        /** @var UriPrimitive|null uri Identifies what this mapping refers to */
        public ?UriPrimitive $uri = null,
        /** @var StringPrimitive|string|null name Names what this mapping refers to */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null comment Versions, Issues, Scope limitations etc. */
        public StringPrimitive|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
