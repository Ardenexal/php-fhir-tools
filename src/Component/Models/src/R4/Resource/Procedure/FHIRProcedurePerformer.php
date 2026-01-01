<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Limited to "real" people rather than equipment.
 */
#[FHIRBackboneElement(parentResource: 'Procedure', elementPath: 'Procedure.performer', fhirVersion: 'R4')]
class FHIRProcedurePerformer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null function Type of performance */
        public ?FHIRCodeableConcept $function = null,
        /** @var FHIRReference|null actor The reference to the practitioner */
        #[NotBlank]
        public ?FHIRReference $actor = null,
        /** @var FHIRReference|null onBehalfOf Organization the device or practitioner was acting for */
        public ?FHIRReference $onBehalfOf = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
