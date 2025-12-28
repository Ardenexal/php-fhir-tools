<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The document and format referenced.  If there are multiple content element repetitions, these must all represent the same document in different format, or attachment metadata.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content', fhirVersion: 'R5')]
class FHIRDocumentReferenceContent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRAttachment|null attachment Where to access the document */
        #[NotBlank]
        public ?\FHIRAttachment $attachment = null,
        /** @var array<FHIRDocumentReferenceContentProfile> profile Content profile rules for the document */
        public array $profile = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
