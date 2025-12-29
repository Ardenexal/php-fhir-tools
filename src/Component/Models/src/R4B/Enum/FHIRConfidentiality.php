<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: Confidentiality
 * URL: http://terminology.hl7.org/ValueSet/v3-Confidentiality
 * Version: 3.0.0
 * Description: Set of codes used to value Act.Confidentiality and Role.Confidentiality attribute in accordance with the definition for concept domain "Confidentiality".
 */
enum FHIRConfidentiality: string
{
	/** Confidentiality */
	case confidentiality = '_Confidentiality';

	/** ConfidentialityByAccessKind */
	case confidentialitybyaccesskind = '_ConfidentialityByAccessKind';

	/** ConfidentialityByInfoType */
	case confidentialitybyinfotype = '_ConfidentialityByInfoType';

	/** ConfidentialityModifiers */
	case confidentialitymodifiers = '_ConfidentialityModifiers';

	/** L */
	case l = 'L';

	/** M */
	case m = 'M';

	/** N */
	case n = 'N';

	/** R */
	case r = 'R';

	/** U */
	case u = 'U';

	/** V */
	case v = 'V';
}
