<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDiagnosticReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
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
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
    fhirVersion: 'R4B',
)]
class FHIRDiagnosticReport extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for report */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn What was requested */
        public array $basedOn = [],
        /** @var FHIRDiagnosticReportStatusType|null status registered | partial | preliminary | final + */
        #[NotBlank]
        public ?FHIRDiagnosticReportStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Service category */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Name/Code for this diagnostic report */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject The subject of the report - usually, but not always, the patient */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Health care event when test ordered */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null effectiveX Clinically relevant time/time-period for report */
        public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
        /** @var FHIRInstant|null issued DateTime this version was made */
        public ?FHIRInstant $issued = null,
        /** @var array<FHIRReference> performer Responsible Diagnostic Service */
        public array $performer = [],
        /** @var array<FHIRReference> resultsInterpreter Primary result interpreter */
        public array $resultsInterpreter = [],
        /** @var array<FHIRReference> specimen Specimens this report is based on */
        public array $specimen = [],
        /** @var array<FHIRReference> result Observations */
        public array $result = [],
        /** @var array<FHIRReference> imagingStudy Reference to full details of imaging associated with the diagnostic report */
        public array $imagingStudy = [],
        /** @var array<FHIRDiagnosticReportMedia> media Key images associated with this report */
        public array $media = [],
        /** @var FHIRString|string|null conclusion Clinical conclusion (interpretation) of test results */
        public FHIRString|string|null $conclusion = null,
        /** @var array<FHIRCodeableConcept> conclusionCode Codes for the clinical conclusion of test results */
        public array $conclusionCode = [],
        /** @var array<FHIRAttachment> presentedForm Entire report as issued */
        public array $presentedForm = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
