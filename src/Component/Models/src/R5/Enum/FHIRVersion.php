<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: FHIRVersion
 * URL: http://hl7.org/fhir/ValueSet/FHIR-version
 * Version: 5.0.0
 * Description: All published FHIR Versions.
 */
enum FHIRVersion: string
{
	/** 0.01 */
	case CODE_001 = '0.01';

	/** 0.05 */
	case CODE_005 = '0.05';

	/** 0.06 */
	case CODE_006 = '0.06';

	/** 0.11 */
	case CODE_011 = '0.11';

	/** 0.0 */
	case CODE_00 = '0.0';

	/** 0.4 */
	case CODE_04 = '0.4';

	/** 0.5 */
	case CODE_05 = '0.5';

	/** 1.0 */
	case CODE_10 = '1.0';

	/** 1.1 */
	case CODE_11 = '1.1';

	/** 1.4 */
	case CODE_14 = '1.4';

	/** 1.6 */
	case CODE_16 = '1.6';

	/** 1.8 */
	case CODE_18 = '1.8';

	/** 3.0 */
	case CODE_30 = '3.0';

	/** 3.3 */
	case CODE_33 = '3.3';

	/** 3.5 */
	case CODE_35 = '3.5';

	/** 4.0 */
	case CODE_40 = '4.0';

	/** 4.1 */
	case CODE_41 = '4.1';

	/** 4.2 */
	case CODE_42 = '4.2';

	/** 4.3 */
	case CODE_43 = '4.3';

	/** 4.4 */
	case CODE_44 = '4.4';

	/** 4.5 */
	case CODE_45 = '4.5';

	/** 4.6 */
	case CODE_46 = '4.6';

	/** 5.0 */
	case CODE_50 = '5.0';
}
