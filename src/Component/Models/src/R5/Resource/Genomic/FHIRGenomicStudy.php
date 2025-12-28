<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/GenomicStudy
 *
 * @description A GenomicStudy is a set of analyses performed to analyze and generate genomic data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'GenomicStudy', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/GenomicStudy', fhirVersion: 'R5')]
class FHIRGenomicStudy extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifiers for this genomic study */
        public array $identifier = [],
        /** @var FHIRGenomicStudyStatusType|null status registered | available | cancelled | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRGenomicStudyStatusType $status = null,
        /** @var array<FHIRCodeableConcept> type The type of the study (e.g., Familial variant segregation, Functional variation detection, or Gene expression profiling) */
        public array $type = [],
        /** @var FHIRReference|null subject The primary subject of the genomic study */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter The healthcare event with which this genomics study is associated */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|null startDate When the genomic study was started */
        public ?\FHIRDateTime $startDate = null,
        /** @var array<FHIRReference> basedOn Event resources that the genomic study is based on */
        public array $basedOn = [],
        /** @var FHIRReference|null referrer Healthcare professional who requested or referred the genomic study */
        public ?\FHIRReference $referrer = null,
        /** @var array<FHIRReference> interpreter Healthcare professionals who interpreted the genomic study */
        public array $interpreter = [],
        /** @var array<FHIRCodeableReference> reason Why the genomic study was performed */
        public array $reason = [],
        /** @var FHIRCanonical|null instantiatesCanonical The defined protocol that describes the study */
        public ?\FHIRCanonical $instantiatesCanonical = null,
        /** @var FHIRUri|null instantiatesUri The URL pointing to an externally maintained protocol that describes the study */
        public ?\FHIRUri $instantiatesUri = null,
        /** @var array<FHIRAnnotation> note Comments related to the genomic study */
        public array $note = [],
        /** @var FHIRMarkdown|null description Description of the genomic study */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRGenomicStudyAnalysis> analysis Genomic Analysis Event */
        public array $analysis = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
