<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/specimen-processingTime
 * @description Period or duration of processing. This is no longer relevant for R6 due to a matching element in R6. This extension is now deprecated.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime', fhirVersion: 'R4')]
class SpecProcessingTimeExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Period|Duration|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/specimen-processingTime',
		    value: $this->value,
		);
	}
}
