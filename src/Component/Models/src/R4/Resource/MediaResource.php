<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EventStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Media
 *
 * @description A photo, video, or audio recording acquired or used in healthcare. The actual content may be inline or provided by direct reference.
 */
#[FhirResource(type: 'Media', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Media', fhirVersion: 'R4')]
class MediaResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifier(s) for the image */
        public array $identifier = [],
        /** @var array<Reference> basedOn Procedure that caused this media to be created */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var EventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?EventStatusType $status = null,
        /** @var CodeableConcept|null type Classification of media as image, video, or audio */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null modality The type of acquisition equipment/process */
        public ?CodeableConcept $modality = null,
        /** @var CodeableConcept|null view Imaging view, e.g. Lateral or Antero-posterior */
        public ?CodeableConcept $view = null,
        /** @var Reference|null subject Who/What this Media is a record of */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter associated with media */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|null createdX When Media was collected */
        public DateTimePrimitive|Period|null $createdX = null,
        /** @var InstantPrimitive|null issued Date/Time this version was made available */
        public ?InstantPrimitive $issued = null,
        /** @var Reference|null operator The person who generated the image */
        public ?Reference $operator = null,
        /** @var array<CodeableConcept> reasonCode Why was event performed? */
        public array $reasonCode = [],
        /** @var CodeableConcept|null bodySite Observed body part */
        public ?CodeableConcept $bodySite = null,
        /** @var StringPrimitive|string|null deviceName Name of the device/manufacturer */
        public StringPrimitive|string|null $deviceName = null,
        /** @var Reference|null device Observing Device */
        public ?Reference $device = null,
        /** @var PositiveIntPrimitive|null height Height of the image in pixels (photo/video) */
        public ?PositiveIntPrimitive $height = null,
        /** @var PositiveIntPrimitive|null width Width of the image in pixels (photo/video) */
        public ?PositiveIntPrimitive $width = null,
        /** @var PositiveIntPrimitive|null frames Number of frames if > 1 (photo) */
        public ?PositiveIntPrimitive $frames = null,
        /** @var float|null duration Length in seconds (audio / video) */
        public ?float $duration = null,
        /** @var Attachment|null content Actual Media - reference or data */
        #[NotBlank]
        public ?Attachment $content = null,
        /** @var array<Annotation> note Comments made about the media */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
