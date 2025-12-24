<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;

/**
 * @description Number of samples in the statistic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.sampleSize', fhirVersion: 'R5')]
class FHIREvidenceStatisticSampleSize extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Textual description of sample size for statistic */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Footnote or explanatory note about the sample size */
        public array $note = [],
        /** @var FHIRUnsignedInt|null numberOfStudies Number of contributing studies */
        public ?FHIRUnsignedInt $numberOfStudies = null,
        /** @var FHIRUnsignedInt|null numberOfParticipants Cumulative number of participants */
        public ?FHIRUnsignedInt $numberOfParticipants = null,
        /** @var FHIRUnsignedInt|null knownDataCount Number of participants with known results for measured variables */
        public ?FHIRUnsignedInt $knownDataCount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
