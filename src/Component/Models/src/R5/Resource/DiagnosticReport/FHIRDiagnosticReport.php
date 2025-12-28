<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport
 *
 * @description The findings and interpretation of diagnostic tests performed on patients, groups of patients, products, substances, devices, and locations, and/or specimens derived from these. The report includes clinical context such as requesting provider information, and some mix of atomic results, images, textual and coded interpretations, and formatted representation of diagnostic reports. The report also includes non-clinical context such as batch analysis and stability reporting of products and substances.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'DiagnosticReport',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
    fhirVersion: 'R5',
)]
class FHIRDiagnosticReport extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier for report */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn What was requested */
        public array $basedOn = [],
        /** @var FHIRDiagnosticReportStatusType|null status registered | partial | preliminary | modified | final | amended | corrected | appended | cancelled | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRDiagnosticReportStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Service category */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Name/Code for this diagnostic report */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject The subject of the report - usually, but not always, the patient */
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Health care event when test ordered */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null effectiveX Clinically relevant time/time-period for report */
        public \FHIRDateTime|\FHIRPeriod|null $effectiveX = null,
        /** @var FHIRInstant|null issued DateTime this version was made */
        public ?\FHIRInstant $issued = null,
        /** @var array<FHIRReference> performer Responsible Diagnostic Service */
        public array $performer = [],
        /** @var array<FHIRReference> resultsInterpreter Primary result interpreter */
        public array $resultsInterpreter = [],
        /** @var array<FHIRReference> specimen Specimens this report is based on */
        public array $specimen = [],
        /** @var array<FHIRReference> result Observations */
        public array $result = [],
        /** @var array<FHIRAnnotation> note Comments about the diagnostic report */
        public array $note = [],
        /** @var array<FHIRReference> study Reference to full details of an analysis associated with the diagnostic report */
        public array $study = [],
        /** @var array<FHIRDiagnosticReportSupportingInfo> supportingInfo Additional information supporting the diagnostic report */
        public array $supportingInfo = [],
        /** @var array<FHIRDiagnosticReportMedia> media Key images or data associated with this report */
        public array $media = [],
        /** @var FHIRReference|null composition Reference to a Composition resource for the DiagnosticReport structure */
        public ?\FHIRReference $composition = null,
        /** @var FHIRMarkdown|null conclusion Clinical conclusion (interpretation) of test results */
        public ?\FHIRMarkdown $conclusion = null,
        /** @var array<FHIRCodeableConcept> conclusionCode Codes for the clinical conclusion of test results */
        public array $conclusionCode = [],
        /** @var array<FHIRAttachment> presentedForm Entire report as issued */
        public array $presentedForm = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
