<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A document definition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.document', fhirVersion: 'R4B')]
class FHIRCapabilityStatementDocument extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDocumentModeType|null mode producer | consumer */
        #[NotBlank]
        public ?FHIRDocumentModeType $mode = null,
        /** @var FHIRMarkdown|null documentation Description of document support */
        public ?FHIRMarkdown $documentation = null,
        /** @var FHIRCanonical|null profile Constraint on the resources used in the document */
        #[NotBlank]
        public ?FHIRCanonical $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
