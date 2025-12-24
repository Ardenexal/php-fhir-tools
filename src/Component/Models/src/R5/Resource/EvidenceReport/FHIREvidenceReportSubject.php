<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description Specifies the subject or focus of the report. Answers "What is this report about?".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceReport', elementPath: 'EvidenceReport.subject', fhirVersion: 'R5')]
class FHIREvidenceReportSubject extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIREvidenceReportSubjectCharacteristic> characteristic Characteristic */
        public array $characteristic = [],
        /** @var array<FHIRAnnotation> note Footnotes and/or explanatory notes */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
