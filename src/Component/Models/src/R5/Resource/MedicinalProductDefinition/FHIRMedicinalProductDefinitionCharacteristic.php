<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Allows the key product features to be recorded, such as "sugar free", "modified release", "parallel import".
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductDefinition',
    elementPath: 'MedicinalProductDefinition.characteristic',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductDefinitionCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type A code expressing the type of characteristic */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|FHIRMarkdown|FHIRQuantity|FHIRInteger|FHIRDate|FHIRBoolean|FHIRAttachment|null valueX A value for the characteristic */
        public \FHIRCodeableConcept|\FHIRMarkdown|\FHIRQuantity|\FHIRInteger|\FHIRDate|\FHIRBoolean|\FHIRAttachment|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
