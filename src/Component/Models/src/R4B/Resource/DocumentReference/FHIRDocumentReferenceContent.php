<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The document and format referenced. There may be multiple content element repetitions, each with a different format.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content', fhirVersion: 'R4B')]
class FHIRDocumentReferenceContent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var FHIRCoding|null format Format/content rules for the document */
        public ?\FHIRCoding $format = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
