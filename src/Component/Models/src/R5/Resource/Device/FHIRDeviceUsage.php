<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDeviceUsageStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUsage
 *
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or a clinician.
 */
#[FhirResource(type: 'DeviceUsage', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/DeviceUsage', fhirVersion: 'R5')]
class FHIRDeviceUsage extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifier for this record */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var FHIRDeviceUsageStatusType|null status active | completed | not-done | entered-in-error + */
        #[NotBlank]
        public ?FHIRDeviceUsageStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category The category of the statement - classifying how the statement is made */
        public array $category = [],
        /** @var FHIRReference|null patient Patient using device */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var array<FHIRReference> derivedFrom Supporting information */
        public array $derivedFrom = [],
        /** @var FHIRReference|null context The encounter or episode of care that establishes the context for this device use statement */
        public ?FHIRReference $context = null,
        /** @var FHIRTiming|FHIRPeriod|FHIRDateTime|null timingX How often  the device was used */
        public FHIRTiming|FHIRPeriod|FHIRDateTime|null $timingX = null,
        /** @var FHIRDateTime|null dateAsserted When the statement was made (and recorded) */
        public ?FHIRDateTime $dateAsserted = null,
        /** @var FHIRCodeableConcept|null usageStatus The status of the device usage, for example always, sometimes, never. This is not the same as the status of the statement */
        public ?FHIRCodeableConcept $usageStatus = null,
        /** @var array<FHIRCodeableConcept> usageReason The reason for asserting the usage status - for example forgot, lost, stolen, broken */
        public array $usageReason = [],
        /** @var FHIRDeviceUsageAdherence|null adherence How device is being used */
        public ?FHIRDeviceUsageAdherence $adherence = null,
        /** @var FHIRReference|null informationSource Who made the statement */
        public ?FHIRReference $informationSource = null,
        /** @var FHIRCodeableReference|null device Code or Reference to device used */
        #[NotBlank]
        public ?FHIRCodeableReference $device = null,
        /** @var array<FHIRCodeableReference> reason Why device was used */
        public array $reason = [],
        /** @var FHIRCodeableReference|null bodySite Target body site */
        public ?FHIRCodeableReference $bodySite = null,
        /** @var array<FHIRAnnotation> note Addition details (comments, instructions) */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
