<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Health Care Devices)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceMetric
 *
 * @description Describes a measurement, calculation or setting capability of a medical device.
 */
#[FhirResource(type: 'DeviceMetric', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DeviceMetric', fhirVersion: 'R4')]
class FHIRDeviceMetric extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type Identity of metric, for example Heart Rate or PEEP Setting */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null unit Unit of Measure for the Metric */
        public ?FHIRCodeableConcept $unit = null,
        /** @var FHIRReference|null source Describes the link to the source Device */
        public ?FHIRReference $source = null,
        /** @var FHIRReference|null parent Describes the link to the parent Device */
        public ?FHIRReference $parent = null,
        /** @var FHIRDeviceMetricOperationalStatusType|null operationalStatus on | off | standby | entered-in-error */
        public ?FHIRDeviceMetricOperationalStatusType $operationalStatus = null,
        /** @var FHIRDeviceMetricColorType|null color black | red | green | yellow | blue | magenta | cyan | white */
        public ?FHIRDeviceMetricColorType $color = null,
        /** @var FHIRDeviceMetricCategoryType|null category measurement | setting | calculation | unspecified */
        #[NotBlank]
        public ?FHIRDeviceMetricCategoryType $category = null,
        /** @var FHIRTiming|null measurementPeriod Describes the measurement repetition time */
        public ?FHIRTiming $measurementPeriod = null,
        /** @var array<FHIRDeviceMetricCalibration> calibration Describes the calibrations that have been performed or that are required to be performed */
        public array $calibration = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
