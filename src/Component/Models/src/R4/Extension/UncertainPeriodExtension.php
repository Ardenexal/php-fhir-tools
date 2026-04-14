<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/uncertainPeriod
 * @description Used to express an uncertain date(+time) value by specifying an allowed or observed upper and lower bound.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/uncertainPeriod', fhirVersion: 'R4')]
class UncertainPeriodExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Period|null valuePeriod Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $valuePeriod = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/uncertainPeriod',
		    value: $this->valuePeriod,
		);
	}
}
