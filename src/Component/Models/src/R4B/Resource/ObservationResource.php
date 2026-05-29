<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SampledData;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Observation\ObservationComponent;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Observation\ObservationReferenceRange;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Observation
 *
 * @description Measurements and simple assertions made about a patient, device or other subject.
 */
#[FhirResource(type: 'Observation', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Observation', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'obs-6',
    severity: 'error',
    expression: 'dataAbsentReason.empty() or value.empty()',
    human: 'dataAbsentReason SHALL only be present if Observation.value[x] is not present',
)]
#[FHIRPathInvariant(
    key: 'obs-7',
    severity: 'error',
    expression: 'value.empty() or component.code.where(coding.intersect(%resource.code.coding).exists()).empty()',
    human: 'If Observation.code is the same as an Observation.component.code then the value element associated with the code SHALL NOT be present',
)]
class ObservationResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies it\'s meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
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
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for observation */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal or order */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/CarePlan',
            'http://hl7.org/fhir/StructureDefinition/DeviceRequest',
            'http://hl7.org/fhir/StructureDefinition/ImmunizationRecommendation',
            'http://hl7.org/fhir/StructureDefinition/MedicationRequest',
            'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
            'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
        ])]
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/MedicationAdministration',
            'http://hl7.org/fhir/StructureDefinition/MedicationDispense',
            'http://hl7.org/fhir/StructureDefinition/MedicationStatement',
            'http://hl7.org/fhir/StructureDefinition/Procedure',
            'http://hl7.org/fhir/StructureDefinition/Immunization',
            'http://hl7.org/fhir/StructureDefinition/ImagingStudy',
        ])]
        public array $partOf = [],
        /** @var ObservationStatusType|null status registered | preliminary | final | amended + */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/observation-status|4.3.0', strength: 'required'), FHIRIsModifier(reason: 'This element is labeled as a modifier because it is a status element that contains status entered-in-error which means that the resource should not be treated as valid')]
        public ?ObservationStatusType $status = null,
        /** @var array<CodeableConcept> category Classification of  type of observation */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/observation-category', strength: 'preferred')]
        public array $category = [],
        /** @var CodeableConcept|null code Type of observation (code / type) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who and/or what the observation is about */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Group',
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/Location',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Procedure',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/Medication',
            'http://hl7.org/fhir/StructureDefinition/Substance',
        ])]
        public ?Reference $subject = null,
        /** @var array<Reference> focus What the observation is about, when it is not about the subject of record */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public array $focus = [],
        /** @var Reference|null encounter Healthcare event during which this observation is made */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Encounter'])]
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|Timing|InstantPrimitive|null effective Clinically relevant time/time-period for observation */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'effectiveDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'effectivePeriod',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Timing',
                    'jsonKey'      => 'effectiveTiming',
                ],
                [
                    'fhirType'     => 'instant',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive',
                    'jsonKey'      => 'effectiveInstant',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Timing|InstantPrimitive|null $effective = null,
        /** @var InstantPrimitive|null issued Date/Time this version was made available */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $issued = null,
        /** @var array<Reference> performer Who is responsible for the observation */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/CareTeam',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
        ])]
        public array $performer = [],
        /** @var Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null value Actual result */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'valueCodeableConcept',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                ],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range',
                    'jsonKey'      => 'valueRange',
                ],
                [
                    'fhirType'     => 'Ratio',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio',
                    'jsonKey'      => 'valueRatio',
                ],
                [
                    'fhirType'     => 'SampledData',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\SampledData',
                    'jsonKey'      => 'valueSampledData',
                ],
                [
                    'fhirType'     => 'time',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive',
                    'jsonKey'      => 'valueTime',
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                ],
            ],
        )]
        public Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null $value = null,
        /** @var CodeableConcept|null dataAbsentReason Why the result is missing */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/data-absent-reason', strength: 'extensible')]
        public ?CodeableConcept $dataAbsentReason = null,
        /** @var array<CodeableConcept> interpretation High, low, normal, etc. */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/observation-interpretation', strength: 'extensible')]
        public array $interpretation = [],
        /** @var array<Annotation> note Comments about the observation */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
        )]
        public array $note = [],
        /** @var CodeableConcept|null bodySite Observed body part */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $bodySite = null,
        /** @var CodeableConcept|null method How it was done */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $method = null,
        /** @var Reference|null specimen Specimen used for this observation */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Specimen'])]
        public ?Reference $specimen = null,
        /** @var Reference|null device (Measurement) Device */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/DeviceMetric',
        ])]
        public ?Reference $device = null,
        /** @var array<ObservationReferenceRange> referenceRange Provides guide for interpretation */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Observation\ObservationReferenceRange',
        )]
        public array $referenceRange = [],
        /** @var array<Reference> hasMember Related resource that belongs to the Observation group */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Observation',
            'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse',
            'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
        ])]
        public array $hasMember = [],
        /** @var array<Reference> derivedFrom Related measurements the observation is made from */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/DocumentReference',
            'http://hl7.org/fhir/StructureDefinition/ImagingStudy',
            'http://hl7.org/fhir/StructureDefinition/Media',
            'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse',
            'http://hl7.org/fhir/StructureDefinition/Observation',
            'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
        ])]
        public array $derivedFrom = [],
        /** @var array<ObservationComponent> component Component results */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Observation\ObservationComponent',
        )]
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
