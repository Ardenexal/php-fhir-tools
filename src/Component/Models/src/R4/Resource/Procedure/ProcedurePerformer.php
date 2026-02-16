<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Procedure;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Limited to "real" people rather than equipment.
 */
#[FHIRBackboneElement(parentResource: 'Procedure', elementPath: 'Procedure.performer', fhirVersion: 'R4')]
class ProcedurePerformer extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null function Type of performance */
        public ?CodeableConcept $function = null,
        /** @var Reference|null actor The reference to the practitioner */
        #[NotBlank]
        public ?Reference $actor = null,
        /** @var Reference|null onBehalfOf Organization the device or practitioner was acting for */
        public ?Reference $onBehalfOf = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
