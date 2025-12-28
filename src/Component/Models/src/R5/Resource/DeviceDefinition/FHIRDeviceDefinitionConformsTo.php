<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the standards, specifications, or formal guidances for the capabilities supported by the device. The device may be certified as conformant to these specifications e.g., communication, performance, process, measurement, or specialization standards.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.conformsTo', fhirVersion: 'R5')]
class FHIRDeviceDefinitionConformsTo extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Describes the common type of the standard, specification, or formal guidance */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null specification Identifies the standard, specification, or formal guidance that the device adheres to the Device Specification type */
        #[NotBlank]
        public ?\FHIRCodeableConcept $specification = null,
        /** @var array<FHIRString|string> version The specific form or variant of the standard, specification or formal guidance */
        public array $version = [],
        /** @var array<FHIRRelatedArtifact> source Standard, regulation, certification, or guidance website, document, or other publication, or similar, supporting the conformance */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
