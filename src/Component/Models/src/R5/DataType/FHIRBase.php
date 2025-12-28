<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Base
 * @description Base definition for all types defined in FHIR type system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Base', fhirVersion: 'R5')]
abstract class FHIRBase
{
	public function __construct()
	{
	}
}
