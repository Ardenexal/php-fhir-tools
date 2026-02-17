<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DiagnosticReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReport\DiagnosticReportMedia;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport
 *
 * @description The findings and interpretation of diagnostic  tests performed on patients, groups of patients, devices, and locations, and/or specimens derived from these. The report includes clinical context such as requesting and provider information, and some mix of atomic results, images, textual and coded interpretations, and formatted representation of diagnostic reports.
 */
#[FhirResource(
    type: 'DiagnosticReport',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
    fhirVersion: 'R4',
)]
class DiagnosticReportResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business identifier for report */
        public array $identifier = [],
        /** @var array<Reference> basedOn What was requested */
        public array $basedOn = [],
        /** @var DiagnosticReportStatusType|null status registered | partial | preliminary | final + */
        #[NotBlank]
        public ?DiagnosticReportStatusType $status = null,
        /** @var array<CodeableConcept> category Service category */
        public array $category = [],
        /** @var CodeableConcept|null code Name/Code for this diagnostic report */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject The subject of the report - usually, but not always, the patient */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Health care event when test ordered */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|null effectiveX Clinically relevant time/time-period for report */
        public DateTimePrimitive|Period|null $effectiveX = null,
        /** @var InstantPrimitive|null issued DateTime this version was made */
        public ?InstantPrimitive $issued = null,
        /** @var array<Reference> performer Responsible Diagnostic Service */
        public array $performer = [],
        /** @var array<Reference> resultsInterpreter Primary result interpreter */
        public array $resultsInterpreter = [],
        /** @var array<Reference> specimen Specimens this report is based on */
        public array $specimen = [],
        /** @var array<Reference> result Observations */
        public array $result = [],
        /** @var array<Reference> imagingStudy Reference to full details of imaging associated with the diagnostic report */
        public array $imagingStudy = [],
        /** @var array<DiagnosticReportMedia> media Key images associated with this report */
        public array $media = [],
        /** @var StringPrimitive|string|null conclusion Clinical conclusion (interpretation) of test results */
        public StringPrimitive|string|null $conclusion = null,
        /** @var array<CodeableConcept> conclusionCode Codes for the clinical conclusion of test results */
        public array $conclusionCode = [],
        /** @var array<Attachment> presentedForm Entire report as issued */
        public array $presentedForm = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
