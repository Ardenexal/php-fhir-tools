<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim detail. Either a simple (a product or service) or a 'group' of sub-details which are simple items.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail', fhirVersion: 'R4')]
class ClaimResponseItemDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null detailSequence Claim detail instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $detailSequence = null,
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<ClaimResponseItemAdjudication> adjudication Detail level adjudication details */
        public array $adjudication = [],
        /** @var array<ClaimResponseItemDetailSubDetail> subDetail Adjudication for claim sub-details */
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
