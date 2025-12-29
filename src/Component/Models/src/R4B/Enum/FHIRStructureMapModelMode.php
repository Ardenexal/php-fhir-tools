<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: StructureMapModelMode
 * URL: http://hl7.org/fhir/ValueSet/map-model-mode
 * Version: 4.3.0
 * Description: How the referenced structure is used in this mapping.
 */
enum FHIRStructureMapModelMode: string
{
	/** Source Structure Definition */
	case sourcestructuredefinition = 'source';

	/** Queried Structure Definition */
	case queriedstructuredefinition = 'queried';

	/** Target Structure Definition */
	case targetstructuredefinition = 'target';

	/** Produced Structure Definition */
	case producedstructuredefinition = 'produced';
}
