<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim detail. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail', fhirVersion: 'R5')]
class FHIRClaimResponseItemDetail extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null detailSequence Claim detail instance identifier */
        #[NotBlank]
        public ?\FHIRPositiveInt $detailSequence = null,
        /** @var array<FHIRIdentifier> traceNumber Number for tracking */
        public array $traceNumber = [],
        /** @var array<FHIRPositiveInt> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var FHIRClaimResponseItemReviewOutcome|null reviewOutcome Detail level adjudication results */
        public ?\FHIRClaimResponseItemReviewOutcome $reviewOutcome = null,
        /** @var array<FHIRClaimResponseItemAdjudication> adjudication Detail level adjudication details */
        public array $adjudication = [],
        /** @var array<FHIRClaimResponseItemDetailSubDetail> subDetail Adjudication for claim sub-details */
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
