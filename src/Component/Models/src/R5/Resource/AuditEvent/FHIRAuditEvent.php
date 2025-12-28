<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 *
 * @description A record of an event relevant for purposes such as operations, privacy, security, maintenance, and performance analysis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AuditEvent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R5')]
class FHIRAuditEvent extends FHIRDomainResource
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
        /** @var array<FHIRCodeableConcept> category Type/identifier of event */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Specific type of event */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRAuditEventActionType|null action Type of action performed during the event */
        public ?\FHIRAuditEventActionType $action = null,
        /** @var FHIRAuditEventSeverityType|null severity emergency | alert | critical | error | warning | notice | informational | debug */
        public ?\FHIRAuditEventSeverityType $severity = null,
        /** @var FHIRPeriod|FHIRDateTime|null occurredX When the activity occurred */
        public \FHIRPeriod|\FHIRDateTime|null $occurredX = null,
        /** @var FHIRInstant|null recorded Time when the event was recorded */
        #[NotBlank]
        public ?\FHIRInstant $recorded = null,
        /** @var FHIRAuditEventOutcome|null outcome Whether the event succeeded or failed */
        public ?\FHIRAuditEventOutcome $outcome = null,
        /** @var array<FHIRCodeableConcept> authorization Authorization related to the event */
        public array $authorization = [],
        /** @var array<FHIRReference> basedOn Workflow authorization within which this event occurred */
        public array $basedOn = [],
        /** @var FHIRReference|null patient The patient is the subject of the data used/created/updated/deleted during the activity */
        public ?\FHIRReference $patient = null,
        /** @var FHIRReference|null encounter Encounter within which this event occurred or which the event is tightly associated */
        public ?\FHIRReference $encounter = null,
        /** @var array<FHIRAuditEventAgent> agent Actor involved in the event */
        public array $agent = [],
        /** @var FHIRAuditEventSource|null source Audit Event Reporter */
        #[NotBlank]
        public ?\FHIRAuditEventSource $source = null,
        /** @var array<FHIRAuditEventEntity> entity Data or objects used */
        public array $entity = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
