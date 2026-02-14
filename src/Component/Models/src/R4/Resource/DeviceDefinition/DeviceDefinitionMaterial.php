<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance used to create the material(s) of which the device is made.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.material', fhirVersion: 'R4')]
class DeviceDefinitionMaterial extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null substance The substance */
        #[NotBlank]
        public ?CodeableConcept $substance = null,
        /** @var bool|null alternate Indicates an alternative material of the device */
        public ?bool $alternate = null,
        /** @var bool|null allergenicIndicator Whether the substance is a known or suspected allergen */
        public ?bool $allergenicIndicator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
