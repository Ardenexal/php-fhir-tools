<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An identifier of the document constraints, encoding, structure, and template that the document conforms to beyond the base format indicated in the mimeType.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content.profile', fhirVersion: 'R5')]
class FHIRDocumentReferenceContentProfile extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|FHIRUri|FHIRCanonical|null valueX Code|uri|canonical */
        #[NotBlank]
        public \FHIRCoding|\FHIRUri|\FHIRCanonical|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
