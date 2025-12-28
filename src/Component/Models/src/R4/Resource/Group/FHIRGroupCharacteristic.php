<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies traits whose presence r absence is shared by members of the group.
 */
#[FHIRBackboneElement(parentResource: 'Group', elementPath: 'Group.characteristic', fhirVersion: 'R4')]
class FHIRGroupCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Kind of characteristic */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|null valueX Value held by characteristic */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRBoolean|FHIRQuantity|FHIRRange|FHIRReference|null $valueX = null,
        /** @var FHIRBoolean|null exclude Group includes or excludes */
        #[NotBlank]
        public ?FHIRBoolean $exclude = null,
        /** @var FHIRPeriod|null period Period over which characteristic is tested */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
