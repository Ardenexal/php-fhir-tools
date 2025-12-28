<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description When using an account for billing a specific Encounter the set of diagnoses that are relevant for billing are stored here on the account where they are able to be sequenced appropriately prior to processing to produce claim(s).
 */
#[FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.diagnosis', fhirVersion: 'R5')]
class FHIRAccountDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Ranking of the diagnosis (for each type) */
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRCodeableReference|null condition The diagnosis relevant to the account */
        #[NotBlank]
        public ?FHIRCodeableReference $condition = null,
        /** @var FHIRDateTime|null dateOfDiagnosis Date of the diagnosis (when coded diagnosis) */
        public ?FHIRDateTime $dateOfDiagnosis = null,
        /** @var array<FHIRCodeableConcept> type Type that this diagnosis has relevant to the account (e.g. admission, billing, discharge …) */
        public array $type = [],
        /** @var FHIRBoolean|null onAdmission Diagnosis present on Admission */
        public ?FHIRBoolean $onAdmission = null,
        /** @var array<FHIRCodeableConcept> packageCode Package Code specific for billing */
        public array $packageCode = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
