<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A component of the method to generate the statistic.
 */
#[FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.modelCharacteristic', fhirVersion: 'R4B')]
class FHIREvidenceStatisticModelCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Model specification */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRQuantity|null value Numerical value to complete model specification */
        public ?\FHIRQuantity $value = null,
        /** @var array<FHIREvidenceStatisticModelCharacteristicVariable> variable A variable adjusted for in the adjusted analysis */
        public array $variable = [],
        /** @var array<FHIREvidenceStatisticAttributeEstimate> attributeEstimate An attribute of the statistic used as a model characteristic */
        public array $attributeEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
