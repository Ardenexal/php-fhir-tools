<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DetectedIssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DetectedIssue\DetectedIssueEvidence;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DetectedIssue\DetectedIssueMitigation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DetectedIssue
 *
 * @description Indicates an actual or potential clinical issue with or between one or more active or proposed clinical actions for a patient; e.g. Drug-drug interaction, Ineffective treatment frequency, Procedure-condition conflict, etc.
 */
#[FhirResource(type: 'DetectedIssue', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DetectedIssue', fhirVersion: 'R4')]
class DetectedIssueResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique id for the detected issue */
        public array $identifier = [],
        /** @var ObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?ObservationStatusType $status = null,
        /** @var CodeableConcept|null code Issue Category, e.g. drug-drug, duplicate therapy, etc. */
        public ?CodeableConcept $code = null,
        /** @var DetectedIssueSeverityType|null severity high | moderate | low */
        public ?DetectedIssueSeverityType $severity = null,
        /** @var Reference|null patient Associated patient */
        public ?Reference $patient = null,
        /** @var DateTimePrimitive|Period|null identifiedX When identified */
        public DateTimePrimitive|Period|null $identifiedX = null,
        /** @var Reference|null author The provider or device that identified the issue */
        public ?Reference $author = null,
        /** @var array<Reference> implicated Problem resource */
        public array $implicated = [],
        /** @var array<DetectedIssueEvidence> evidence Supporting evidence */
        public array $evidence = [],
        /** @var StringPrimitive|string|null detail Description and context */
        public StringPrimitive|string|null $detail = null,
        /** @var UriPrimitive|null reference Authority for issue */
        public ?UriPrimitive $reference = null,
        /** @var array<DetectedIssueMitigation> mitigation Step taken to address */
        public array $mitigation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
