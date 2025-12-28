<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Whether a treatment instruction (e.g. artificial respiration: yes or no) was verified with the patient, his/her family or another authorized person.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.verification', fhirVersion: 'R5')]
class FHIRConsentVerification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null verified Has been verified */
        #[NotBlank]
        public ?\FHIRBoolean $verified = null,
        /** @var FHIRCodeableConcept|null verificationType Business case of verification */
        public ?\FHIRCodeableConcept $verificationType = null,
        /** @var FHIRReference|null verifiedBy Person conducting verification */
        public ?\FHIRReference $verifiedBy = null,
        /** @var FHIRReference|null verifiedWith Person who verified */
        public ?\FHIRReference $verifiedWith = null,
        /** @var array<FHIRDateTime> verificationDate When consent verified */
        public array $verificationDate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
