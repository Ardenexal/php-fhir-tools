<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance used to create the material(s) of which the device is made.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.material', fhirVersion: 'R5')]
class FHIRDeviceDefinitionMaterial extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null substance A relevant substance that the device contains, may contain, or is made of */
        #[NotBlank]
        public ?FHIRCodeableConcept $substance = null,
        /** @var FHIRBoolean|null alternate Indicates an alternative material of the device */
        public ?FHIRBoolean $alternate = null,
        /** @var FHIRBoolean|null allergenicIndicator Whether the substance is a known or suspected allergen */
        public ?FHIRBoolean $allergenicIndicator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
