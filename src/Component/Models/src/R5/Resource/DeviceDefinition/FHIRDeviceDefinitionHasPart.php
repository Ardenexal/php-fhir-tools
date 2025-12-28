<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A device that is part (for example a component) of the present device.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.hasPart', fhirVersion: 'R5')]
class FHIRDeviceDefinitionHasPart extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null reference Reference to the part */
        #[NotBlank]
        public ?\FHIRReference $reference = null,
        /** @var FHIRInteger|null count Number of occurrences of the part */
        public ?\FHIRInteger $count = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
