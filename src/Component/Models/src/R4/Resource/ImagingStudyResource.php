<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ImagingStudyStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy\ImagingStudySeries;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Imaging Integration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImagingStudy
 *
 * @description Representation of the content produced in a DICOM imaging study. A study comprises a set of series, each of which includes a set of Service-Object Pair Instances (SOP Instances - images or other data) acquired or produced in a common context.  A series is of only one modality (e.g. X-ray, CT, MR, ultrasound), but a study may have multiple series of different modalities.
 */
#[FhirResource(type: 'ImagingStudy', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ImagingStudy', fhirVersion: 'R4')]
class ImagingStudyResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifiers for the whole study */
        public array $identifier = [],
        /** @var ImagingStudyStatusType|null status registered | available | cancelled | entered-in-error | unknown */
        #[NotBlank]
        public ?ImagingStudyStatusType $status = null,
        /** @var array<Coding> modality All series modality if actual acquisition modalities */
        public array $modality = [],
        /** @var Reference|null subject Who or what is the subject of the study */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter with which this imaging study is associated */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null started When the study was started */
        public ?DateTimePrimitive $started = null,
        /** @var array<Reference> basedOn Request fulfilled */
        public array $basedOn = [],
        /** @var Reference|null referrer Referring physician */
        public ?Reference $referrer = null,
        /** @var array<Reference> interpreter Who interpreted images */
        public array $interpreter = [],
        /** @var array<Reference> endpoint Study access endpoint */
        public array $endpoint = [],
        /** @var UnsignedIntPrimitive|null numberOfSeries Number of Study Related Series */
        public ?UnsignedIntPrimitive $numberOfSeries = null,
        /** @var UnsignedIntPrimitive|null numberOfInstances Number of Study Related Instances */
        public ?UnsignedIntPrimitive $numberOfInstances = null,
        /** @var Reference|null procedureReference The performed Procedure reference */
        public ?Reference $procedureReference = null,
        /** @var array<CodeableConcept> procedureCode The performed procedure code */
        public array $procedureCode = [],
        /** @var Reference|null location Where ImagingStudy occurred */
        public ?Reference $location = null,
        /** @var array<CodeableConcept> reasonCode Why the study was requested */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why was study performed */
        public array $reasonReference = [],
        /** @var array<Annotation> note User-defined comments */
        public array $note = [],
        /** @var StringPrimitive|string|null description Institution-generated description */
        public StringPrimitive|string|null $description = null,
        /** @var array<ImagingStudySeries> series Each study has one or more series of instances */
        public array $series = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
