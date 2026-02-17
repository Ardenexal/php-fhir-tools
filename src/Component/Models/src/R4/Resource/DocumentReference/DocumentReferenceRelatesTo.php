<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentRelationshipTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Relationships that this document has with other document references that already exist.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.relatesTo', fhirVersion: 'R4')]
class DocumentReferenceRelatesTo extends BackboneElement
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
        /** @var Reference|null target Target of the relationship */
        #[NotBlank]
        public ?Reference $target = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
