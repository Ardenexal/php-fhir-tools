<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.translation', fhirVersion: 'R4')]
class TerminologyCapabilitiesTranslation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null needsMap Whether the client must identify the map */
        #[NotBlank]
        public ?bool $needsMap = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
