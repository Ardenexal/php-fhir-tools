<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingStudy;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each study has one or more series of images or other content.
 */
#[FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series', fhirVersion: 'R5')]
class ImagingStudySeries extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var IdPrimitive|null uid DICOM Series Instance UID for the series */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $uid = null,
        /** @var UnsignedIntPrimitive|null number Numeric identifier of this series */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $number = null,
        /** @var CodeableConcept|null modality The modality used for this series */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $modality = null,
        /** @var StringPrimitive|string|null description A short human readable summary of the series */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var UnsignedIntPrimitive|null numberOfInstances Number of Series Related Instances */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $numberOfInstances = null,
        /** @var array<Reference> endpoint Series access endpoint */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $endpoint = [],
        /** @var CodeableReference|null bodySite Body part examined */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $bodySite = null,
        /** @var CodeableConcept|null laterality Body part laterality */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $laterality = null,
        /** @var array<Reference> specimen Specimen imaged */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $specimen = [],
        /** @var DateTimePrimitive|null started When the series started */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $started = null,
        /** @var array<ImagingStudySeriesPerformer> performer Who performed the series */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingStudy\ImagingStudySeriesPerformer',
        )]
        public array $performer = [],
        /** @var array<ImagingStudySeriesInstance> instance A single SOP instance from the series */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingStudy\ImagingStudySeriesInstance',
        )]
        public array $instance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
