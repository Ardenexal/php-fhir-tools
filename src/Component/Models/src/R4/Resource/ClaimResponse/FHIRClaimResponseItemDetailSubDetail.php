<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sub-detail adjudication of a simple product or service.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail.subDetail', fhirVersion: 'R4')]
class FHIRClaimResponseItemDetailSubDetail extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null subDetailSequence Claim sub-detail instance identifier */
        #[NotBlank]
        public ?\FHIRPositiveInt $subDetailSequence = null,
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<FHIRClaimResponseItemAdjudication> adjudication Subdetail level adjudication details */
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
