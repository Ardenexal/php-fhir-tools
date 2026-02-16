<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Device capabilities.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.capability', fhirVersion: 'R4')]
class DeviceDefinitionCapability extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of capability */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> description Description of capability */
        public array $description = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
