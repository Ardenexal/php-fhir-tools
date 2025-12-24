<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description The details about a specific analysis that was performed in this GenomicStudy.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GenomicStudy', elementPath: 'GenomicStudy.analysis', fhirVersion: 'R5')]
class FHIRGenomicStudyAnalysis extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifiers for the analysis event */
        public array $identifier = [],
        /** @var array<FHIRCodeableConcept> methodType Type of the methods used in the analysis (e.g., FISH, Karyotyping, MSI) */
        public array $methodType = [],
        /** @var array<FHIRCodeableConcept> changeType Type of the genomic changes studied in the analysis (e.g., DNA, RNA, or AA change) */
        public array $changeType = [],
        /** @var FHIRCodeableConcept|null genomeBuild Genome build that is used in this analysis */
        public ?FHIRCodeableConcept $genomeBuild = null,
        /** @var FHIRCanonical|null instantiatesCanonical The defined protocol that describes the analysis */
        public ?FHIRCanonical $instantiatesCanonical = null,
        /** @var FHIRUri|null instantiatesUri The URL pointing to an externally maintained protocol that describes the analysis */
        public ?FHIRUri $instantiatesUri = null,
        /** @var FHIRString|string|null title Name of the analysis event (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var array<FHIRReference> focus What the genomic analysis is about, when it is not about the subject of record */
        public array $focus = [],
        /** @var array<FHIRReference> specimen The specimen used in the analysis event */
        public array $specimen = [],
        /** @var FHIRDateTime|null date The date of the analysis event */
        public ?FHIRDateTime $date = null,
        /** @var array<FHIRAnnotation> note Any notes capture with the analysis event */
        public array $note = [],
        /** @var FHIRReference|null protocolPerformed The protocol that was performed for the analysis event */
        public ?FHIRReference $protocolPerformed = null,
        /** @var array<FHIRReference> regionsStudied The genomic regions to be studied in the analysis (BED file) */
        public array $regionsStudied = [],
        /** @var array<FHIRReference> regionsCalled Genomic regions actually called in the analysis event (BED file) */
        public array $regionsCalled = [],
        /** @var array<FHIRGenomicStudyAnalysisInput> input Inputs for the analysis event */
        public array $input = [],
        /** @var array<FHIRGenomicStudyAnalysisOutput> output Outputs for the analysis event */
        public array $output = [],
        /** @var array<FHIRGenomicStudyAnalysisPerformer> performer Performer for the analysis event */
        public array $performer = [],
        /** @var array<FHIRGenomicStudyAnalysisDevice> device Devices used for the analysis (e.g., instruments, software), with settings and parameters */
        public array $device = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
