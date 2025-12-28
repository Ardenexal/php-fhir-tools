<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Imaging Integration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImagingSelection
 *
 * @description A selection of DICOM SOP instances and/or frames within a single Study and Series. This might include additional specifics such as an image region, an Observation UID or a Segmentation Number, allowing linkage to an Observation Resource or transferring this information along with the ImagingStudy Resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ImagingSelection',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ImagingSelection',
    fhirVersion: 'R5',
)]
class FHIRImagingSelection extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for Imaging Selection */
        public array $identifier = [],
        /** @var FHIRImagingSelectionStatusType|null status available | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRImagingSelectionStatusType $status = null,
        /** @var FHIRReference|null subject Subject of the selected instances */
        public ?\FHIRReference $subject = null,
        /** @var FHIRInstant|null issued Date / Time when this imaging selection was created */
        public ?\FHIRInstant $issued = null,
        /** @var array<FHIRImagingSelectionPerformer> performer Selector of the instances (human or machine) */
        public array $performer = [],
        /** @var array<FHIRReference> basedOn Associated request */
        public array $basedOn = [],
        /** @var array<FHIRCodeableConcept> category Classifies the imaging selection */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Imaging Selection purpose text or code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRId|null studyUid DICOM Study Instance UID */
        public ?\FHIRId $studyUid = null,
        /** @var array<FHIRReference> derivedFrom The imaging study from which the imaging selection is derived */
        public array $derivedFrom = [],
        /** @var array<FHIRReference> endpoint The network service providing retrieval for the images referenced in the imaging selection */
        public array $endpoint = [],
        /** @var FHIRId|null seriesUid DICOM Series Instance UID */
        public ?\FHIRId $seriesUid = null,
        /** @var FHIRUnsignedInt|null seriesNumber DICOM Series Number */
        public ?\FHIRUnsignedInt $seriesNumber = null,
        /** @var FHIRId|null frameOfReferenceUid The Frame of Reference UID for the selected images */
        public ?\FHIRId $frameOfReferenceUid = null,
        /** @var FHIRCodeableReference|null bodySite Body part examined */
        public ?\FHIRCodeableReference $bodySite = null,
        /** @var array<FHIRReference> focus Related resource that is the focus for the imaging selection */
        public array $focus = [],
        /** @var array<FHIRImagingSelectionInstance> instance The selected instances */
        public array $instance = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
