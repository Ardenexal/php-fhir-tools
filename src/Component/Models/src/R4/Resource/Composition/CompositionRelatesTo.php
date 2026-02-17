<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentRelationshipTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Relationships that this composition has with other compositions or documents that already exist.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.relatesTo', fhirVersion: 'R4')]
class CompositionRelatesTo extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DocumentRelationshipTypeType|null code replaces | transforms | signs | appends */
        #[NotBlank]
        public ?DocumentRelationshipTypeType $code = null,
        /** @var Identifier|Reference|null targetX Target of the relationship */
        #[NotBlank]
        public Identifier|Reference|null $targetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
