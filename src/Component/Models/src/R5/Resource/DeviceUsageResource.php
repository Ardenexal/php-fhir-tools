<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DeviceUsageStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceUsage\DeviceUsageAdherence;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUsage
 *
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or a clinician.
 */
#[FhirResource(type: 'DeviceUsage', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/DeviceUsage', fhirVersion: 'R5')]
class DeviceUsageResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier for this record */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal or order */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $basedOn = [],
        /** @var DeviceUsageStatusType|null status active | completed | not-done | entered-in-error + */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DeviceUsageStatusType $status = null,
        /** @var array<CodeableConcept> category The category of the statement - classifying how the statement is made */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var Reference|null patient Patient using device */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $patient = null,
        /** @var array<Reference> derivedFrom Supporting information */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $derivedFrom = [],
        /** @var Reference|null context The encounter or episode of care that establishes the context for this device use statement */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $context = null,
        /** @var Timing|Period|DateTimePrimitive|null timing How often  the device was used */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Timing',
                    'jsonKey'      => 'timingTiming',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'timingPeriod',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'timingDateTime',
                ],
            ],
        )]
        public Timing|Period|DateTimePrimitive|null $timing = null,
        /** @var DateTimePrimitive|null dateAsserted When the statement was made (and recorded) */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $dateAsserted = null,
        /** @var CodeableConcept|null usageStatus The status of the device usage, for example always, sometimes, never. This is not the same as the status of the statement */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $usageStatus = null,
        /** @var array<CodeableConcept> usageReason The reason for asserting the usage status - for example forgot, lost, stolen, broken */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $usageReason = [],
        /** @var DeviceUsageAdherence|null adherence How device is being used */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?DeviceUsageAdherence $adherence = null,
        /** @var Reference|null informationSource Who made the statement */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $informationSource = null,
        /** @var CodeableReference|null device Code or Reference to device used */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableReference $device = null,
        /** @var array<CodeableReference> reason Why device was used */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $reason = [],
        /** @var CodeableReference|null bodySite Target body site */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $bodySite = null,
        /** @var array<Annotation> note Addition details (comments, instructions) */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
