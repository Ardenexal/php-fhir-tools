<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The calculated account balances - these are calculated and processed by the finance system.
 *
 * The balances with a `term` that is not current are usually generated/updated by an invoicing or similar process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.balance', fhirVersion: 'R5')]
class FHIRAccountBalance extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null aggregate Who is expected to pay this part of the balance */
        public ?FHIRCodeableConcept $aggregate = null,
        /** @var FHIRCodeableConcept|null term current | 30 | 60 | 90 | 120 */
        public ?FHIRCodeableConcept $term = null,
        /** @var FHIRBoolean|null estimate Estimated balance */
        public ?FHIRBoolean $estimate = null,
        /** @var FHIRMoney|null amount Calculated amount */
        #[NotBlank]
        public ?FHIRMoney $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
