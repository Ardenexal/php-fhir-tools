<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventActionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventOutcomeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventAgent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventEntity;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventSource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 *
 * @description A record of an event made for purposes of maintaining a security log. Typical uses include detection of intrusion attempts and monitoring for inappropriate usage.
 */
#[FhirResource(type: 'AuditEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R4')]
class AuditEventResource extends DomainResourceResource
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
        /** @var Coding|null type Type/identifier of event */
        #[NotBlank]
        public ?Coding $type = null,
        /** @var array<Coding> subtype More specific type/id for the event */
        public array $subtype = [],
        /** @var AuditEventActionType|null action Type of action performed during the event */
        public ?AuditEventActionType $action = null,
        /** @var Period|null period When the activity occurred */
        public ?Period $period = null,
        /** @var InstantPrimitive|null recorded Time when the event was recorded */
        #[NotBlank]
        public ?InstantPrimitive $recorded = null,
        /** @var AuditEventOutcomeType|null outcome Whether the event succeeded or failed */
        public ?AuditEventOutcomeType $outcome = null,
        /** @var StringPrimitive|string|null outcomeDesc Description of the event outcome */
        public StringPrimitive|string|null $outcomeDesc = null,
        /** @var array<CodeableConcept> purposeOfEvent The purposeOfUse of the event */
        public array $purposeOfEvent = [],
        /** @var array<AuditEventAgent> agent Actor involved in the event */
        public array $agent = [],
        /** @var AuditEventSource|null source Audit Event Reporter */
        #[NotBlank]
        public ?AuditEventSource $source = null,
        /** @var array<AuditEventEntity> entity Data or objects used */
        public array $entity = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
