<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 * @see http://hl7.org/fhir/StructureDefinition/GenomicStudy
 * @description A GenomicStudy is a set of analyses performed to analyze and generate genomic data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'GenomicStudy', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/GenomicStudy', fhirVersion: 'R5')]
class FHIRGenomicStudy extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Identifiers for this genomic study */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRGenomicStudyStatusType status registered | available | cancelled | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRGenomicStudyStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> type The type of the study (e.g., Familial variant segregation, Functional variation detection, or Gene expression profiling) */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference subject The primary subject of the genomic study */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference encounter The healthcare event with which this genomics study is associated */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime startDate When the genomic study was started */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $startDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> basedOn Event resources that the genomic study is based on */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference referrer Healthcare professional who requested or referred the genomic study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $referrer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> interpreter Healthcare professionals who interpreted the genomic study */
		public array $interpreter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> reason Why the genomic study was performed */
		public array $reason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical instantiatesCanonical The defined protocol that describes the study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri instantiatesUri The URL pointing to an externally maintained protocol that describes the study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $instantiatesUri = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Comments related to the genomic study */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Description of the genomic study */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGenomicStudyAnalysis> analysis Genomic Analysis Event */
		public array $analysis = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
