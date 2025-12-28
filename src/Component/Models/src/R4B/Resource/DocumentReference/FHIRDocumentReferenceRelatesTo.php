<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Relationships that this document has with other document references that already exist.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.relatesTo', fhirVersion: 'R4B')]
class FHIRDocumentReferenceRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        public ?FHIRDocumentRelationshipTypeType $code = null,
        /** @var FHIRReference|null target Target of the relationship */
        #[NotBlank]
        public ?FHIRReference $target = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
