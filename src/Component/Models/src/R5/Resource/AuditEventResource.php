<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AuditEventActionType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AuditEventSeverityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventAgent;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventEntity;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventSource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 *
 * @description A record of an event relevant for purposes such as operations, privacy, security, maintenance, and performance analysis.
 */
#[FhirResource(type: 'AuditEvent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R5')]
class AuditEventResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> category Type/identifier of event */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var CodeableConcept|null code Specific type of event */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var AuditEventActionType|null action Type of action performed during the event */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AuditEventActionType $action = null,
        /** @var AuditEventSeverityType|null severity emergency | alert | critical | error | warning | notice | informational | debug */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AuditEventSeverityType $severity = null,
        /** @var Period|DateTimePrimitive|null occurred When the activity occurred */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'occurredPeriod',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'occurredDateTime',
                ],
            ],
        )]
        public Period|DateTimePrimitive|null $occurred = null,
        /** @var InstantPrimitive|null recorded Time when the event was recorded */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?InstantPrimitive $recorded = null,
        /** @var AuditEventOutcome|null outcome Whether the event succeeded or failed */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?AuditEventOutcome $outcome = null,
        /** @var array<CodeableConcept> authorization Authorization related to the event */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $authorization = [],
        /** @var array<Reference> basedOn Workflow authorization within which this event occurred */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $basedOn = [],
        /** @var Reference|null patient The patient is the subject of the data used/created/updated/deleted during the activity */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $patient = null,
        /** @var Reference|null encounter Encounter within which this event occurred or which the event is tightly associated */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $encounter = null,
        /** @var array<AuditEventAgent> agent Actor involved in the event */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventAgent',
        )]
        public array $agent = [],
        /** @var AuditEventSource|null source Audit Event Reporter */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isRequired: true), NotBlank]
        public ?AuditEventSource $source = null,
        /** @var array<AuditEventEntity> entity Data or objects used */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\AuditEvent\AuditEventEntity',
        )]
        public array $entity = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
