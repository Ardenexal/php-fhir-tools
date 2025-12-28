<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the entity validating information.
 */
#[FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.validator', fhirVersion: 'R4B')]
class FHIRVerificationResultValidator extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null organization Reference to the organization validating information */
        #[NotBlank]
        public ?\FHIRReference $organization = null,
        /** @var FHIRString|string|null identityCertificate A digital identity certificate associated with the validator */
        public \FHIRString|string|null $identityCertificate = null,
        /** @var FHIRSignature|null attestationSignature Validator signature */
        public ?\FHIRSignature $attestationSignature = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
