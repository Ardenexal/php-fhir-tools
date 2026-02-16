<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationComponent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation\ObservationReferenceRange;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Observation
 *
 * @description Measurements and simple assertions made about a patient, device or other subject.
 */
#[FhirResource(type: 'Observation', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Observation', fhirVersion: 'R4')]
class ObservationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for observation */
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var ObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?ObservationStatusType $status = null,
        /** @var array<CodeableConcept> category Classification of  type of observation */
        public array $category = [],
        /** @var CodeableConcept|null code Type of observation (code / type) */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who and/or what the observation is about */
        public ?Reference $subject = null,
        /** @var array<Reference> focus What the observation is about, when it is not about the subject of record */
        public array $focus = [],
        /** @var Reference|null encounter Healthcare event during which this observation is made */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|Timing|InstantPrimitive|null effectiveX Clinically relevant time/time-period for observation */
        public DateTimePrimitive|Period|Timing|InstantPrimitive|null $effectiveX = null,
        /** @var InstantPrimitive|null issued Date/Time this version was made available */
        public ?InstantPrimitive $issued = null,
        /** @var array<Reference> performer Who is responsible for the observation */
        public array $performer = [],
        /** @var Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null valueX Actual result */
        public Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null $valueX = null,
        /** @var CodeableConcept|null dataAbsentReason Why the result is missing */
        public ?CodeableConcept $dataAbsentReason = null,
        /** @var array<CodeableConcept> interpretation High, low, normal, etc. */
        public array $interpretation = [],
        /** @var array<Annotation> note Comments about the observation */
        public array $note = [],
        /** @var CodeableConcept|null bodySite Observed body part */
        public ?CodeableConcept $bodySite = null,
        /** @var CodeableConcept|null method How it was done */
        public ?CodeableConcept $method = null,
        /** @var Reference|null specimen Specimen used for this observation */
        public ?Reference $specimen = null,
        /** @var Reference|null device (Measurement) Device */
        public ?Reference $device = null,
        /** @var array<ObservationReferenceRange> referenceRange Provides guide for interpretation */
        public array $referenceRange = [],
        /** @var array<Reference> hasMember Related resource that belongs to the Observation group */
        public array $hasMember = [],
        /** @var array<Reference> derivedFrom Related measurements the observation is made from */
        public array $derivedFrom = [],
        /** @var array<ObservationComponent> component Component results */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
