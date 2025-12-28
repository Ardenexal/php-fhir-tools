<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
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
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUseStatement
 *
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or another clinician.
 */
#[FhirResource(
    type: 'DeviceUseStatement',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceUseStatement',
    fhirVersion: 'R5',
)]
class FHIRDeviceUseStatement extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifier for this record */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var FHIRDeviceUseStatementStatusType|null status active | completed | entered-in-error + */
        #[NotBlank]
        public ?FHIRDeviceUseStatementStatusType $status = null,
        /** @var FHIRReference|null subject Patient using device */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var array<FHIRReference> derivedFrom Supporting information */
        public array $derivedFrom = [],
        /** @var FHIRTiming|FHIRPeriod|FHIRDateTime|null timingX How often  the device was used */
        public FHIRTiming|FHIRPeriod|FHIRDateTime|null $timingX = null,
        /** @var FHIRDateTime|null recordedOn When statement was recorded */
        public ?FHIRDateTime $recordedOn = null,
        /** @var FHIRReference|null source Who made the statement */
        public ?FHIRReference $source = null,
        /** @var FHIRReference|null device Reference to device used */
        #[NotBlank]
        public ?FHIRReference $device = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why device was used */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why was DeviceUseStatement performed? */
        public array $reasonReference = [],
        /** @var FHIRCodeableConcept|null bodySite Target body site */
        public ?FHIRCodeableConcept $bodySite = null,
        /** @var array<FHIRAnnotation> note Addition details (comments, instructions) */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
