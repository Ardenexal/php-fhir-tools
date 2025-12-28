<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Errors encountered during the processing of the adjudication.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.error', fhirVersion: 'R4B')]
class FHIRClaimResponseError extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null itemSequence Item sequence number */
        public ?FHIRPositiveInt $itemSequence = null,
        /** @var FHIRPositiveInt|null detailSequence Detail sequence number */
        public ?FHIRPositiveInt $detailSequence = null,
        /** @var FHIRPositiveInt|null subDetailSequence Subdetail sequence number */
        public ?FHIRPositiveInt $subDetailSequence = null,
        /** @var FHIRCodeableConcept|null code Error code detailing processing issues */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
