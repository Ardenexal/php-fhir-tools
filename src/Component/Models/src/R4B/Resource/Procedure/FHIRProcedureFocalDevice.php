<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A device that is implanted, removed or otherwise manipulated (calibration, battery replacement, fitting a prosthesis, attaching a wound-vac, etc.) as a focal portion of the Procedure.
 */
#[FHIRBackboneElement(parentResource: 'Procedure', elementPath: 'Procedure.focalDevice', fhirVersion: 'R4B')]
class FHIRProcedureFocalDevice extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null action Kind of change to device */
        public ?\FHIRCodeableConcept $action = null,
        /** @var FHIRReference|null manipulated Device that was changed */
        #[NotBlank]
        public ?\FHIRReference $manipulated = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
