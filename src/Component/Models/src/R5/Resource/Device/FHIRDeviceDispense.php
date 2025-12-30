<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDeviceDispenseStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDispense
 *
 * @description A record of dispensation of a device - i.e., assigning a device to a patient, or to a professional for their use.
 */
#[FhirResource(
    type: 'DeviceDispense',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceDispense',
    fhirVersion: 'R5',
)]
class FHIRDeviceDispense extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier for this dispensation */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn The order or request that this dispense is fulfilling */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf The bigger event that this dispense is a part of */
        public array $partOf = [],
        /** @var FHIRDeviceDispenseStatusCodesType|null status preparation | in-progress | cancelled | on-hold | completed | entered-in-error | stopped | declined | unknown */
        #[NotBlank]
        public ?FHIRDeviceDispenseStatusCodesType $status = null,
        /** @var FHIRCodeableReference|null statusReason Why a dispense was or was not performed */
        public ?FHIRCodeableReference $statusReason = null,
        /** @var array<FHIRCodeableConcept> category Type of device dispense */
        public array $category = [],
        /** @var FHIRCodeableReference|null device What device was supplied */
        #[NotBlank]
        public ?FHIRCodeableReference $device = null,
        /** @var FHIRReference|null subject Who the dispense is for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null receiver Who collected the device or where the medication was delivered */
        public ?FHIRReference $receiver = null,
        /** @var FHIRReference|null encounter Encounter associated with event */
        public ?FHIRReference $encounter = null,
        /** @var array<FHIRReference> supportingInformation Information that supports the dispensing of the device */
        public array $supportingInformation = [],
        /** @var array<FHIRDeviceDispensePerformer> performer Who performed event */
        public array $performer = [],
        /** @var FHIRReference|null location Where the dispense occurred */
        public ?FHIRReference $location = null,
        /** @var FHIRCodeableConcept|null type Trial fill, partial fill, emergency fill, etc */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity Amount dispensed */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRDateTime|null preparedDate When product was packaged and reviewed */
        public ?FHIRDateTime $preparedDate = null,
        /** @var FHIRDateTime|null whenHandedOver When product was given out */
        public ?FHIRDateTime $whenHandedOver = null,
        /** @var FHIRReference|null destination Where the device was sent or should be sent */
        public ?FHIRReference $destination = null,
        /** @var array<FHIRAnnotation> note Information about the dispense */
        public array $note = [],
        /** @var FHIRMarkdown|null usageInstruction Full representation of the usage instructions */
        public ?FHIRMarkdown $usageInstruction = null,
        /** @var array<FHIRReference> eventHistory A list of relevant lifecycle events */
        public array $eventHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
