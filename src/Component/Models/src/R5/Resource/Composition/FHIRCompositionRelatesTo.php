<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Relationships that this composition has with other compositions or documents that already exist.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.relatesTo', fhirVersion: 'R4B')]
class FHIRCompositionRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDocumentRelationshipTypeType|null code replaces | transforms | signs | appends */
        #[NotBlank]
        public ?\FHIRDocumentRelationshipTypeType $code = null,
        /** @var FHIRIdentifier|FHIRReference|null targetX Target of the relationship */
        #[NotBlank]
        public \FHIRIdentifier|\FHIRReference|null $targetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
