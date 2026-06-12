<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ImagingStudyStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ImagingStudy\ImagingStudySeries;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Imaging Integration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImagingStudy
 *
 * @description Representation of the content produced in a DICOM imaging study. A study comprises a set of series, each of which includes a set of Service-Object Pair Instances (SOP Instances - images or other data) acquired or produced in a common context.  A series is of only one modality (e.g. X-ray, CT, MR, ultrasound), but a study may have multiple series of different modalities.
 */
#[FhirResource(type: 'ImagingStudy', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/ImagingStudy', fhirVersion: 'R4B')]
class ImagingStudyResource extends AbstractDomainResource
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
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Identifiers for the whole study */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var ImagingStudyStatusType|null status registered | available | cancelled | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/imagingstudy-status|4.3.0', strength: 'required'), FHIRIsModifier(reason: 'This element is labelled as a modifier because it is a status element that contains status entered-in-error which means that the resource should not be treated as valid')]
        public ?ImagingStudyStatusType $status = null,
        /** @var array<Coding> modality All series modality if actual acquisition modalities */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://dicom.nema.org/medical/dicom/current/output/chtml/part16/sect_CID_29.html', strength: 'extensible')]
        public array $modality = [],
        /** @var Reference|null subject Who or what is the subject of the study */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true)]
        #[NotBlank]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/Group',
        ])]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter with which this imaging study is associated */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Encounter'])]
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null started When the study was started */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $started = null,
        /** @var array<Reference> basedOn Request fulfilled */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/CarePlan',
            'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
            'http://hl7.org/fhir/StructureDefinition/Appointment',
            'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
            'http://hl7.org/fhir/StructureDefinition/Task',
        ])]
        public array $basedOn = [],
        /** @var Reference|null referrer Referring physician */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
        ])]
        public ?Reference $referrer = null,
        /** @var array<Reference> interpreter Who interpreted images */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
        ])]
        public array $interpreter = [],
        /** @var array<Reference> endpoint Study access endpoint */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Endpoint'])]
        public array $endpoint = [],
        /** @var UnsignedIntPrimitive|null numberOfSeries Number of Study Related Series */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $numberOfSeries = null,
        /** @var UnsignedIntPrimitive|null numberOfInstances Number of Study Related Instances */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $numberOfInstances = null,
        /** @var Reference|null procedureReference The performed Procedure reference */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Procedure'])]
        public ?Reference $procedureReference = null,
        /** @var array<CodeableConcept> procedureCode The performed procedure code */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://www.rsna.org/RadLex_Playbook.aspx', strength: 'extensible')]
        public array $procedureCode = [],
        /** @var Reference|null location Where ImagingStudy occurred */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Location'])]
        public ?Reference $location = null,
        /** @var array<CodeableConcept> reasonCode Why the study was requested */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why was study performed */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Condition',
            'http://hl7.org/fhir/StructureDefinition/Observation',
            'http://hl7.org/fhir/StructureDefinition/Media',
            'http://hl7.org/fhir/StructureDefinition/DiagnosticReport',
            'http://hl7.org/fhir/StructureDefinition/DocumentReference',
        ])]
        public array $reasonReference = [],
        /** @var array<Annotation> note User-defined comments */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
        )]
        public array $note = [],
        /** @var StringPrimitive|string|null description Institution-generated description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var array<ImagingStudySeries> series Each study has one or more series of instances */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ImagingStudy\ImagingStudySeries',
        )]
        public array $series = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
