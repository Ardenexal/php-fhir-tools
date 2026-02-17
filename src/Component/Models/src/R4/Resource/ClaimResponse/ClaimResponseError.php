<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Errors encountered during the processing of the adjudication.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.error', fhirVersion: 'R4')]
class ClaimResponseError extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null itemSequence Item sequence number */
        public ?PositiveIntPrimitive $itemSequence = null,
        /** @var PositiveIntPrimitive|null detailSequence Detail sequence number */
        public ?PositiveIntPrimitive $detailSequence = null,
        /** @var PositiveIntPrimitive|null subDetailSequence Subdetail sequence number */
        public ?PositiveIntPrimitive $subDetailSequence = null,
        /** @var CodeableConcept|null code Error code detailing processing issues */
        #[NotBlank]
        public ?CodeableConcept $code = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
