<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The document and format referenced. There may be multiple content element repetitions, each with a different format.
 */
#[FHIRBackboneElement(parentResource: 'DocumentReference', elementPath: 'DocumentReference.content', fhirVersion: 'R4')]
class DocumentReferenceContent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Attachment|null attachment Where to access the document */
        #[NotBlank]
        public ?Attachment $attachment = null,
        /** @var Coding|null format Format/content rules for the document */
        public ?Coding $format = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
