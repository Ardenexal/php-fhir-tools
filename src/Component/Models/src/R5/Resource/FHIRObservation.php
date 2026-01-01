<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSampledData;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Observation
 *
 * @description Measurements and simple assertions made about a patient, device or other subject.
 */
#[FhirResource(type: 'Observation', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Observation', fhirVersion: 'R5')]
class FHIRObservation extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for observation */
        public array $identifier = [],
        /** @var FHIRCanonical|FHIRReference|null instantiatesX Instantiates FHIR ObservationDefinition */
        public FHIRCanonical|FHIRReference|null $instantiatesX = null,
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var array<FHIRObservationTriggeredBy> triggeredBy Triggering observation(s) */
        public array $triggeredBy = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?FHIRObservationStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Classification of  type of observation */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Type of observation (code / type) */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who and/or what the observation is about */
        public ?FHIRReference $subject = null,
        /** @var array<FHIRReference> focus What the observation is about, when it is not about the subject of record */
        public array $focus = [],
        /** @var FHIRReference|null encounter Healthcare event during which this observation is made */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|FHIRInstant|null effectiveX Clinically relevant time/time-period for observation */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|FHIRInstant|null $effectiveX = null,
        /** @var FHIRInstant|null issued Date/Time this version was made available */
        public ?FHIRInstant $issued = null,
        /** @var array<FHIRReference> performer Who is responsible for the observation */
        public array $performer = [],
        /** @var FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRRatio|FHIRSampledData|FHIRTime|FHIRDateTime|FHIRPeriod|FHIRAttachment|FHIRReference|null valueX Actual result */
        public FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRRatio|FHIRSampledData|FHIRTime|FHIRDateTime|FHIRPeriod|FHIRAttachment|FHIRReference|null $valueX = null,
        /** @var FHIRCodeableConcept|null dataAbsentReason Why the result is missing */
        public ?FHIRCodeableConcept $dataAbsentReason = null,
        /** @var array<FHIRCodeableConcept> interpretation High, low, normal, etc */
        public array $interpretation = [],
        /** @var array<FHIRAnnotation> note Comments about the observation */
        public array $note = [],
        /** @var FHIRCodeableConcept|null bodySite Observed body part */
        public ?FHIRCodeableConcept $bodySite = null,
        /** @var FHIRReference|null bodyStructure Observed body structure */
        public ?FHIRReference $bodyStructure = null,
        /** @var FHIRCodeableConcept|null method How it was done */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRReference|null specimen Specimen used for this observation */
        public ?FHIRReference $specimen = null,
        /** @var FHIRReference|null device A reference to the device that generates the measurements or the device settings for the device */
        public ?FHIRReference $device = null,
        /** @var array<FHIRObservationReferenceRange> referenceRange Provides guide for interpretation */
        public array $referenceRange = [],
        /** @var array<FHIRReference> hasMember Related resource that belongs to the Observation group */
        public array $hasMember = [],
        /** @var array<FHIRReference> derivedFrom Related resource from which the observation is made */
        public array $derivedFrom = [],
        /** @var array<FHIRObservationComponent> component Component results */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
