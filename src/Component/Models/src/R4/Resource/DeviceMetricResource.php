<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricColorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricOperationalStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceMetric\DeviceMetricCalibration;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Health Care Devices)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceMetric
 *
 * @description Describes a measurement, calculation or setting capability of a medical device.
 */
#[FhirResource(type: 'DeviceMetric', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DeviceMetric', fhirVersion: 'R4')]
class DeviceMetricResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var CodeableConcept|null type Identity of metric, for example Heart Rate or PEEP Setting */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null unit Unit of Measure for the Metric */
        public ?CodeableConcept $unit = null,
        /** @var Reference|null source Describes the link to the source Device */
        public ?Reference $source = null,
        /** @var Reference|null parent Describes the link to the parent Device */
        public ?Reference $parent = null,
        /** @var DeviceMetricOperationalStatusType|null operationalStatus on | off | standby | entered-in-error */
        public ?DeviceMetricOperationalStatusType $operationalStatus = null,
        /** @var DeviceMetricColorType|null color black | red | green | yellow | blue | magenta | cyan | white */
        public ?DeviceMetricColorType $color = null,
        /** @var DeviceMetricCategoryType|null category measurement | setting | calculation | unspecified */
        #[NotBlank]
        public ?DeviceMetricCategoryType $category = null,
        /** @var Timing|null measurementPeriod Describes the measurement repetition time */
        public ?Timing $measurementPeriod = null,
        /** @var array<DeviceMetricCalibration> calibration Describes the calibrations that have been performed or that are required to be performed */
        public array $calibration = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
