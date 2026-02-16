<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the entity validating information.
 */
#[FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.validator', fhirVersion: 'R4')]
class VerificationResultValidator extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null organization Reference to the organization validating information */
        #[NotBlank]
        public ?Reference $organization = null,
        /** @var StringPrimitive|string|null identityCertificate A digital identity certificate associated with the validator */
        public StringPrimitive|string|null $identityCertificate = null,
        /** @var Signature|null attestationSignature Validator signature */
        public ?Signature $attestationSignature = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
