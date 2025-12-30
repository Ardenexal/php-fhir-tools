<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAuditEventActionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAuditEventOutcomeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 *
 * @description A record of an event made for purposes of maintaining a security log. Typical uses include detection of intrusion attempts and monitoring for inappropriate usage.
 */
#[FhirResource(type: 'AuditEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R4')]
class FHIRAuditEvent extends FHIRDomainResource
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
        /** @var FHIRCoding|null type Type/identifier of event */
        #[NotBlank]
        public ?FHIRCoding $type = null,
        /** @var array<FHIRCoding> subtype More specific type/id for the event */
        public array $subtype = [],
        /** @var FHIRAuditEventActionType|null action Type of action performed during the event */
        public ?FHIRAuditEventActionType $action = null,
        /** @var FHIRPeriod|null period When the activity occurred */
        public ?FHIRPeriod $period = null,
        /** @var FHIRInstant|null recorded Time when the event was recorded */
        #[NotBlank]
        public ?FHIRInstant $recorded = null,
        /** @var FHIRAuditEventOutcomeType|null outcome Whether the event succeeded or failed */
        public ?FHIRAuditEventOutcomeType $outcome = null,
        /** @var FHIRString|string|null outcomeDesc Description of the event outcome */
        public FHIRString|string|null $outcomeDesc = null,
        /** @var array<FHIRCodeableConcept> purposeOfEvent The purposeOfUse of the event */
        public array $purposeOfEvent = [],
        /** @var array<FHIRAuditEventAgent> agent Actor involved in the event */
        public array $agent = [],
        /** @var FHIRAuditEventSource|null source Audit Event Reporter */
        #[NotBlank]
        public ?FHIRAuditEventSource $source = null,
        /** @var array<FHIRAuditEventEntity> entity Data or objects used */
        public array $entity = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
