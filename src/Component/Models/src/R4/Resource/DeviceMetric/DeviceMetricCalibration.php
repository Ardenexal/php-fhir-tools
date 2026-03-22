<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceMetric;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationStateType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceMetricCalibrationTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;

/**
 * @description Describes the calibrations that have been performed or that are required to be performed.
 */
#[FHIRBackboneElement(parentResource: 'DeviceMetric', elementPath: 'DeviceMetric.calibration', fhirVersion: 'R4')]
class DeviceMetricCalibration extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var DeviceMetricCalibrationTypeType|null type unspecified | offset | gain | two-point */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?DeviceMetricCalibrationTypeType $type = null,
        /** @var DeviceMetricCalibrationStateType|null state not-calibrated | calibration-required | calibrated | unspecified */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?DeviceMetricCalibrationStateType $state = null,
        /** @var InstantPrimitive|null time Describes the time last calibration has been performed */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $time = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
