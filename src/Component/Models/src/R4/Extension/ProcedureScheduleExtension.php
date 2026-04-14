<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/procedure-schedule
 * @description The schedule that was followed. Use Procedure.occurrenceTiming in R5+
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-schedule', fhirVersion: 'R4')]
class ProcedureScheduleExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Timing|null valueTiming Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Timing', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing $valueTiming = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/procedure-schedule',
		    value: $this->valueTiming,
		);
	}
}
