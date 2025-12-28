<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DetectedIssue
 *
 * @description Indicates an actual or potential clinical issue with or between one or more active or proposed clinical actions for a patient; e.g. Drug-drug interaction, Ineffective treatment frequency, Procedure-condition conflict, etc.
 */
#[FhirResource(type: 'DetectedIssue', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DetectedIssue', fhirVersion: 'R4')]
class FHIRDetectedIssue extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique id for the detected issue */
        public array $identifier = [],
        /** @var FHIRObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?FHIRObservationStatusType $status = null,
        /** @var FHIRCodeableConcept|null code Issue Category, e.g. drug-drug, duplicate therapy, etc. */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRDetectedIssueSeverityType|null severity high | moderate | low */
        public ?FHIRDetectedIssueSeverityType $severity = null,
        /** @var FHIRReference|null patient Associated patient */
        public ?FHIRReference $patient = null,
        /** @var FHIRDateTime|FHIRPeriod|null identifiedX When identified */
        public FHIRDateTime|FHIRPeriod|null $identifiedX = null,
        /** @var FHIRReference|null author The provider or device that identified the issue */
        public ?FHIRReference $author = null,
        /** @var array<FHIRReference> implicated Problem resource */
        public array $implicated = [],
        /** @var array<FHIRDetectedIssueEvidence> evidence Supporting evidence */
        public array $evidence = [],
        /** @var FHIRString|string|null detail Description and context */
        public FHIRString|string|null $detail = null,
        /** @var FHIRUri|null reference Authority for issue */
        public ?FHIRUri $reference = null,
        /** @var array<FHIRDetectedIssueMitigation> mitigation Step taken to address */
        public array $mitigation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
