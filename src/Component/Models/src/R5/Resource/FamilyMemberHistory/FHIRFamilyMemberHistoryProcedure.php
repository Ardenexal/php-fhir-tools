<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The significant Procedures (or procedure) that the family member had. This is a repeating section to allow a system to represent more than one procedure per resource, though there is nothing stopping multiple resources - one per procedure.
 */
#[FHIRBackboneElement(parentResource: 'FamilyMemberHistory', elementPath: 'FamilyMemberHistory.procedure', fhirVersion: 'R5')]
class FHIRFamilyMemberHistoryProcedure extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Procedures performed on the related person */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null outcome What happened following the procedure */
        public ?FHIRCodeableConcept $outcome = null,
        /** @var FHIRBoolean|null contributedToDeath Whether the procedure contributed to the cause of death */
        public ?FHIRBoolean $contributedToDeath = null,
        /** @var FHIRAge|FHIRRange|FHIRPeriod|FHIRString|string|FHIRDateTime|null performedX When the procedure was performed */
        public FHIRAge|FHIRRange|FHIRPeriod|FHIRString|string|FHIRDateTime|null $performedX = null,
        /** @var array<FHIRAnnotation> note Extra information about the procedure */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
