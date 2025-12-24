<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Imaging Integration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImagingStudy
 *
 * @description Representation of the content produced in a DICOM imaging study. A study comprises a set of series, each of which includes a set of Service-Object Pair Instances (SOP Instances - images or other data) acquired or produced in a common context.  A series is of only one modality (e.g. X-ray, CT, MR, ultrasound), but a study may have multiple series of different modalities.
 */
#[FhirResource(type: 'ImagingStudy', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ImagingStudy', fhirVersion: 'R5')]
class FHIRImagingStudy extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifiers for the whole study */
        public array $identifier = [],
        /** @var FHIRImagingStudyStatusType|null status registered | available | cancelled | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRImagingStudyStatusType $status = null,
        /** @var array<FHIRCodeableConcept> modality All of the distinct values for series' modalities */
        public array $modality = [],
        /** @var FHIRReference|null subject Who or what is the subject of the study */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter with which this imaging study is associated */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null started When the study was started */
        public ?FHIRDateTime $started = null,
        /** @var array<FHIRReference> basedOn Request fulfilled */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRReference|null referrer Referring physician */
        public ?FHIRReference $referrer = null,
        /** @var array<FHIRReference> endpoint Study access endpoint */
        public array $endpoint = [],
        /** @var FHIRUnsignedInt|null numberOfSeries Number of Study Related Series */
        public ?FHIRUnsignedInt $numberOfSeries = null,
        /** @var FHIRUnsignedInt|null numberOfInstances Number of Study Related Instances */
        public ?FHIRUnsignedInt $numberOfInstances = null,
        /** @var array<FHIRCodeableReference> procedure The performed procedure or code */
        public array $procedure = [],
        /** @var FHIRReference|null location Where ImagingStudy occurred */
        public ?FHIRReference $location = null,
        /** @var array<FHIRCodeableReference> reason Why the study was requested / performed */
        public array $reason = [],
        /** @var array<FHIRAnnotation> note User-defined comments */
        public array $note = [],
        /** @var FHIRString|string|null description Institution-generated description */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRImagingStudySeries> series Each study has one or more series of instances */
        public array $series = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
