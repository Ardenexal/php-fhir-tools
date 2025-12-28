<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: v3 Code System Confidentiality
 * URL: http://terminology.hl7.org/ValueSet/v3-Confidentiality
 * Version: 2018-08-12
 * Description:  A set of codes specifying the security classification of acts and roles in accordance with the definition for concept domain "Confidentiality".
 */
enum FHIRV3Confidentiality: string
{
	/** Confidentiality */
	case confidentiality = '_Confidentiality';

	/** ConfidentialityByAccessKind */
	case confidentialitybyaccesskind = '_ConfidentialityByAccessKind';

	/** ConfidentialityByInfoType */
	case confidentialitybyinfotype = '_ConfidentialityByInfoType';

	/** ConfidentialityModifiers */
	case confidentialitymodifiers = '_ConfidentialityModifiers';
}
