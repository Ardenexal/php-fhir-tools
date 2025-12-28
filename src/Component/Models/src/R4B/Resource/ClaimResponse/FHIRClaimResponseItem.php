<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim line. Either a simple (a product or service) or a 'group' of details which can also be a simple items or groups of sub-details.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item', fhirVersion: 'R4B')]
class FHIRClaimResponseItem extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null itemSequence Claim item instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $itemSequence = null,
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<FHIRClaimResponseItemAdjudication> adjudication Adjudication details */
        public array $adjudication = [],
        /** @var array<FHIRClaimResponseItemDetail> detail Adjudication for claim details */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
