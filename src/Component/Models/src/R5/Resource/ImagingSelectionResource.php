<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ImagingSelectionStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingSelection\ImagingSelectionInstance;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingSelection\ImagingSelectionPerformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Imaging Integration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImagingSelection
 *
 * @description A selection of DICOM SOP instances and/or frames within a single Study and Series. This might include additional specifics such as an image region, an Observation UID or a Segmentation Number, allowing linkage to an Observation Resource or transferring this information along with the ImagingStudy Resource.
 */
#[FhirResource(
    type: 'ImagingSelection',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ImagingSelection',
    fhirVersion: 'R5',
)]
class ImagingSelectionResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
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
        /** @var array<Identifier> identifier Business Identifier for Imaging Selection */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var ImagingSelectionStatusType|null status available | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/imagingselection-status|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This element is labeled as a modifier because it is a status element that contains status entered-in-error which means that the resource should not be treated as valid')]
        public ?ImagingSelectionStatusType $status = null,
        /** @var Reference|null subject Subject of the selected instances */
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
            'http://hl7.org/fhir/StructureDefinition/Specimen',
        ])]
        public ?Reference $subject = null,
        /** @var InstantPrimitive|null issued Date / Time when this imaging selection was created */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $issued = null,
        /** @var array<ImagingSelectionPerformer> performer Selector of the instances (human or machine) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingSelection\ImagingSelectionPerformer',
        )]
        public array $performer = [],
        /** @var array<Reference> basedOn Associated request */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/CarePlan',
            'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
            'http://hl7.org/fhir/StructureDefinition/Appointment',
            'http://hl7.org/fhir/StructureDefinition/AppointmentResponse',
            'http://hl7.org/fhir/StructureDefinition/Task',
        ])]
        public array $basedOn = [],
        /** @var array<CodeableConcept> category Classifies the imaging selection */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var CodeableConcept|null code Imaging Selection purpose text or code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var IdPrimitive|null studyUid DICOM Study Instance UID */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $studyUid = null,
        /** @var array<Reference> derivedFrom The imaging study from which the imaging selection is derived */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/ImagingStudy',
            'http://hl7.org/fhir/StructureDefinition/DocumentReference',
        ])]
        public array $derivedFrom = [],
        /** @var array<Reference> endpoint The network service providing retrieval for the images referenced in the imaging selection */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Endpoint'])]
        public array $endpoint = [],
        /** @var IdPrimitive|null seriesUid DICOM Series Instance UID */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $seriesUid = null,
        /** @var UnsignedIntPrimitive|null seriesNumber DICOM Series Number */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $seriesNumber = null,
        /** @var IdPrimitive|null frameOfReferenceUid The Frame of Reference UID for the selected images */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $frameOfReferenceUid = null,
        /** @var CodeableReference|null bodySite Body part examined */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/BodyStructure'])]
        public ?CodeableReference $bodySite = null,
        /** @var array<Reference> focus Related resource that is the focus for the imaging selection */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ImagingSelection'])]
        public array $focus = [],
        /** @var array<ImagingSelectionInstance> instance The selected instances */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingSelection\ImagingSelectionInstance',
        )]
        public array $instance = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
