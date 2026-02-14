<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Whether a treatment instruction (e.g. artificial respiration yes or no) was verified with the patient, his/her family or another authorized person.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.verification', fhirVersion: 'R4')]
class ConsentVerification extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null verified Has been verified */
        #[NotBlank]
        public ?bool $verified = null,
        /** @var Reference|null verifiedWith Person who verified */
        public ?Reference $verifiedWith = null,
        /** @var DateTimePrimitive|null verificationDate When consent verified */
        public ?DateTimePrimitive $verificationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
