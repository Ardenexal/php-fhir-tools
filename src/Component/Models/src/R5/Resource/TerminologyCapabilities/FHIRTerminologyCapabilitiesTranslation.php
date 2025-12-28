<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.translation', fhirVersion: 'R5')]
class FHIRTerminologyCapabilitiesTranslation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null needsMap Whether the client must identify the map */
        #[NotBlank]
        public ?FHIRBoolean $needsMap = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
