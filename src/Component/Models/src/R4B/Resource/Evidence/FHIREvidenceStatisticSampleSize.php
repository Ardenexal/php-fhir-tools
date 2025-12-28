<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Number of samples in the statistic.
 */
#[FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.sampleSize', fhirVersion: 'R4B')]
class FHIREvidenceStatisticSampleSize extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Textual description of sample size for statistic */
        public \FHIRString|string|null $description = null,
        /** @var array<FHIRAnnotation> note Footnote or explanatory note about the sample size */
        public array $note = [],
        /** @var FHIRUnsignedInt|null numberOfStudies Number of contributing studies */
        public ?\FHIRUnsignedInt $numberOfStudies = null,
        /** @var FHIRUnsignedInt|null numberOfParticipants Cumulative number of participants */
        public ?\FHIRUnsignedInt $numberOfParticipants = null,
        /** @var FHIRUnsignedInt|null knownDataCount Number of participants with known results for measured variables */
        public ?\FHIRUnsignedInt $knownDataCount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
