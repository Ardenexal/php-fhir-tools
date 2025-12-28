<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Media
 *
 * @description A photo, video, or audio recording acquired or used in healthcare. The actual content may be inline or provided by direct reference.
 */
#[FhirResource(type: 'Media', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Media', fhirVersion: 'R5')]
class FHIRMedia extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier(s) for the image */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Procedure that caused this media to be created */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIREventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIREventStatusType $status = null,
        /** @var FHIRCodeableConcept|null type Classification of media as image, video, or audio */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null modality The type of acquisition equipment/process */
        public ?FHIRCodeableConcept $modality = null,
        /** @var FHIRCodeableConcept|null view Imaging view, e.g. Lateral or Antero-posterior */
        public ?FHIRCodeableConcept $view = null,
        /** @var FHIRReference|null subject Who/What this Media is a record of */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter associated with media */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null createdX When Media was collected */
        public FHIRDateTime|FHIRPeriod|null $createdX = null,
        /** @var FHIRInstant|null issued Date/Time this version was made available */
        public ?FHIRInstant $issued = null,
        /** @var FHIRReference|null operator The person who generated the image */
        public ?FHIRReference $operator = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why was event performed? */
        public array $reasonCode = [],
        /** @var FHIRCodeableConcept|null bodySite Observed body part */
        public ?FHIRCodeableConcept $bodySite = null,
        /** @var FHIRString|string|null deviceName Name of the device/manufacturer */
        public FHIRString|string|null $deviceName = null,
        /** @var FHIRReference|null device Observing Device */
        public ?FHIRReference $device = null,
        /** @var FHIRPositiveInt|null height Height of the image in pixels (photo/video) */
        public ?FHIRPositiveInt $height = null,
        /** @var FHIRPositiveInt|null width Width of the image in pixels (photo/video) */
        public ?FHIRPositiveInt $width = null,
        /** @var FHIRPositiveInt|null frames Number of frames if > 1 (photo) */
        public ?FHIRPositiveInt $frames = null,
        /** @var FHIRDecimal|null duration Length in seconds (audio / video) */
        public ?FHIRDecimal $duration = null,
        /** @var FHIRAttachment|null content Actual Media - reference or data */
        #[NotBlank]
        public ?FHIRAttachment $content = null,
        /** @var array<FHIRAnnotation> note Comments made about the media */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
