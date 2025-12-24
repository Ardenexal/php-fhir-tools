<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The regulatory procedure for granting or amending a marketing authorization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductAuthorization',
    elementPath: 'MedicinalProductAuthorization.procedure',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductAuthorizationProcedure extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Identifier for this procedure */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null type Type of procedure */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRPeriod|FHIRDateTime|null dateX Date of procedure */
        public FHIRPeriod|FHIRDateTime|null $dateX = null,
        /** @var array<FHIRMedicinalProductAuthorizationProcedure> application Applcations submitted to obtain a marketing authorization */
        public array $application = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
