<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.property', fhirVersion: 'R4')]
class DeviceDefinitionProperty extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Code that specifies the property DeviceDefinitionPropetyCode (Extensible) */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var array<Quantity> valueQuantity Property value as a quantity */
        public array $valueQuantity = [],
        /** @var array<CodeableConcept> valueCode Property value as a code, e.g., NTP4 (synced to NTP) */
        public array $valueCode = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
