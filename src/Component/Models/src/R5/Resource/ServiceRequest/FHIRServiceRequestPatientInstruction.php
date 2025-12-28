<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Instructions in terms that are understood by the patient or consumer.
 */
#[FHIRBackboneElement(parentResource: 'ServiceRequest', elementPath: 'ServiceRequest.patientInstruction', fhirVersion: 'R5')]
class FHIRServiceRequestPatientInstruction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|FHIRReference|null instructionX Patient or consumer-oriented instructions */
        public \FHIRMarkdown|\FHIRReference|null $instructionX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
