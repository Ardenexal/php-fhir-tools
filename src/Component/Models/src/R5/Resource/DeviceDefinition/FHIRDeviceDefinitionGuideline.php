<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Information aimed at providing directions for the usage of this model of device.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.guideline', fhirVersion: 'R5')]
class FHIRDeviceDefinitionGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRUsageContext> useContext The circumstances that form the setting for using the device */
        public array $useContext = [],
        /** @var FHIRMarkdown|null usageInstruction Detailed written and visual directions for the user on how to use the device */
        public ?FHIRMarkdown $usageInstruction = null,
        /** @var array<FHIRRelatedArtifact> relatedArtifact A source of information or reference for this guideline */
        public array $relatedArtifact = [],
        /** @var array<FHIRCodeableConcept> indication A clinical condition for which the device was designed to be used */
        public array $indication = [],
        /** @var array<FHIRCodeableConcept> contraindication A specific situation when a device should not be used because it may cause harm */
        public array $contraindication = [],
        /** @var array<FHIRCodeableConcept> warning Specific hazard alert information that a user needs to know before using the device */
        public array $warning = [],
        /** @var FHIRString|string|null intendedUse A description of the general purpose or medical use of the device or its function */
        public FHIRString|string|null $intendedUse = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
