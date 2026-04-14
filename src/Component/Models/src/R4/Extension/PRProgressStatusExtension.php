<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/procedure-progressStatus
 * @description A code to track a detailed progress of  a procedure (e.g. In Recovery, Prepared for Surgery).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-progressStatus', fhirVersion: 'R4')]
class PRProgressStatusExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodeableConcept|null valueCodeableConcept Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueCodeableConcept = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/procedure-progressStatus',
		    value: $this->valueCodeableConcept,
		);
	}
}
