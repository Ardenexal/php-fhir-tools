<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What kind of device or device system this is.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.classification', fhirVersion: 'R5')]
class FHIRDeviceDefinitionClassification extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type A classification or risk class of the device model */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRRelatedArtifact> justification Further information qualifying this classification of the device model */
        public array $justification = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
