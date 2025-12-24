<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Whether a treatment instruction (e.g. artificial respiration yes or no) was verified with the patient, his/her family or another authorized person.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.verification', fhirVersion: 'R4')]
class FHIRConsentVerification extends FHIRBackboneElement
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
        public ?FHIRBoolean $verified = null,
        /** @var FHIRReference|null verifiedWith Person who verified */
        public ?FHIRReference $verifiedWith = null,
        /** @var FHIRDateTime|null verificationDate When consent verified */
        public ?FHIRDateTime $verificationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
