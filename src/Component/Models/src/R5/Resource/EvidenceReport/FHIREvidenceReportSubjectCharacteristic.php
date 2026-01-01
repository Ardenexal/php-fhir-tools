<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Characteristic.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceReport', elementPath: 'EvidenceReport.subject.characteristic', fhirVersion: 'R5')]
class FHIREvidenceReportSubjectCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Characteristic code */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|null valueX Characteristic value */
        #[NotBlank]
        public FHIRReference|FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|null $valueX = null,
        /** @var FHIRBoolean|null exclude Is used to express not the characteristic */
        public ?FHIRBoolean $exclude = null,
        /** @var FHIRPeriod|null period Timeframe for the characteristic */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
