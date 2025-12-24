<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description When using an account for billing a specific Encounter the set of procedures that are relevant for billing are stored here on the account where they are able to be sequenced appropriately prior to processing to produce claim(s).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.procedure', fhirVersion: 'R5')]
class FHIRAccountProcedure extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Ranking of the procedure (for each type) */
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRCodeableReference|null code The procedure relevant to the account */
        #[NotBlank]
        public ?FHIRCodeableReference $code = null,
        /** @var FHIRDateTime|null dateOfService Date of the procedure (when coded procedure) */
        public ?FHIRDateTime $dateOfService = null,
        /** @var array<FHIRCodeableConcept> type How this procedure value should be used in charging the account */
        public array $type = [],
        /** @var array<FHIRCodeableConcept> packageCode Package Code specific for billing */
        public array $packageCode = [],
        /** @var array<FHIRReference> device Any devices that were associated with the procedure */
        public array $device = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
