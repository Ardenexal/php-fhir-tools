<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description Set of instructions for preservation/transport of the specimen at a defined temperature interval, prior the testing process.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested.handling', fhirVersion: 'R5')]
class FHIRSpecimenDefinitionTypeTestedHandling extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null temperatureQualifier Qualifies the interval of temperature */
        public ?FHIRCodeableConcept $temperatureQualifier = null,
        /** @var FHIRRange|null temperatureRange Temperature range for these handling instructions */
        public ?FHIRRange $temperatureRange = null,
        /** @var FHIRDuration|null maxDuration Maximum preservation time */
        public ?FHIRDuration $maxDuration = null,
        /** @var FHIRMarkdown|null instruction Preservation instruction */
        public ?FHIRMarkdown $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
