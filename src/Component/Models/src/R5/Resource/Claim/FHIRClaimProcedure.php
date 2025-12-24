<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Procedures performed on the patient relevant to the billing items with the claim.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.procedure', fhirVersion: 'R5')]
class FHIRClaimProcedure extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Procedure instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var array<FHIRCodeableConcept> type Category of Procedure */
        public array $type = [],
        /** @var FHIRDateTime|null date When the procedure was performed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRCodeableConcept|FHIRReference|null procedureX Specific clinical procedure */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $procedureX = null,
        /** @var array<FHIRReference> udi Unique device identifier */
        public array $udi = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
