<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The regulatory procedure for granting or amending a marketing authorization.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductAuthorization',
    elementPath: 'MedicinalProductAuthorization.procedure',
    fhirVersion: 'R4',
)]
class MedicinalProductAuthorizationProcedure extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Identifier for this procedure */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null type Type of procedure */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Period|DateTimePrimitive|null dateX Date of procedure */
        public Period|DateTimePrimitive|null $dateX = null,
        /** @var array<MedicinalProductAuthorizationProcedure> application Applcations submitted to obtain a marketing authorization */
        public array $application = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
