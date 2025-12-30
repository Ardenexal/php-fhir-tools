<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Link to the paying party and optionally what specifically they will be responsible to pay.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.paymentBy', fhirVersion: 'R5')]
class FHIRCoveragePaymentBy extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null party Parties performing self-payment */
        #[NotBlank]
        public ?FHIRReference $party = null,
        /** @var FHIRString|string|null responsibility Party's responsibility */
        public FHIRString|string|null $responsibility = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
