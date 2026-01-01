<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The ameliorating action taken after the adverse event occured in order to reduce the extent of harm.
 */
#[FHIRBackboneElement(parentResource: 'AdverseEvent', elementPath: 'AdverseEvent.mitigatingAction', fhirVersion: 'R5')]
class FHIRAdverseEventMitigatingAction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|FHIRCodeableConcept|null itemX Ameliorating action taken after the adverse event occured in order to reduce the extent of harm */
        #[NotBlank]
        public FHIRReference|FHIRCodeableConcept|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
