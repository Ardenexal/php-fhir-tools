<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: DeviceMetricCalibrationType
 * URL: http://hl7.org/fhir/ValueSet/metric-calibration-type
 * Version: 4.0.1
 * Description: Describes the type of a metric calibration.
 */
enum FHIRDeviceMetricCalibrationType: string
{
	/** Unspecified */
	case unspecified = 'unspecified';

	/** Offset */
	case offset = 'offset';

	/** Gain */
	case gain = 'gain';

	/** Two Point */
	case twopoint = 'two-point';
}
