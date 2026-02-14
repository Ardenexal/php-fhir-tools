<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sub-detail adjudication of a simple product or service.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.item.detail.subDetail', fhirVersion: 'R4')]
class ClaimResponseItemDetailSubDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null subDetailSequence Claim sub-detail instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $subDetailSequence = null,
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        public array $noteNumber = [],
        /** @var array<ClaimResponseItemAdjudication> adjudication Subdetail level adjudication details */
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
