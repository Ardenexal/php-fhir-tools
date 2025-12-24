<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Billing code or reference associated with the device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.chargeItem', fhirVersion: 'R5')]
class FHIRDeviceDefinitionChargeItem extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null chargeItemCode The code or reference for the charge item */
        #[NotBlank]
        public ?FHIRCodeableReference $chargeItemCode = null,
        /** @var FHIRQuantity|null count Coefficient applicable to the billing code */
        #[NotBlank]
        public ?FHIRQuantity $count = null,
        /** @var FHIRPeriod|null effectivePeriod A specific time period in which this charge item applies */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRUsageContext> useContext The context to which this charge item applies */
        public array $useContext = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
