<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The required capability must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata', fhirVersion: 'R4')]
class FHIRTestScriptMetadata extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRTestScriptMetadataLink> link Links to the FHIR specification */
        public array $link = [],
        /** @var array<FHIRTestScriptMetadataCapability> capability Capabilities  that are assumed to function correctly on the FHIR server being tested */
        public array $capability = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
