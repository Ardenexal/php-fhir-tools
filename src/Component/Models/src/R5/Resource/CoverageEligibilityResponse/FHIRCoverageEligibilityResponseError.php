<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Errors encountered during the processing of the request.
 */
#[FHIRBackboneElement(parentResource: 'CoverageEligibilityResponse', elementPath: 'CoverageEligibilityResponse.error', fhirVersion: 'R5')]
class FHIRCoverageEligibilityResponseError extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Error code detailing processing issues */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRString|string> expression FHIRPath of element(s) related to issue */
        public array $expression = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
