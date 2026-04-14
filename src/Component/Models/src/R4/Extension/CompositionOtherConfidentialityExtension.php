<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Structured Documents
 * @see http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality
 * @description Carries additional confidentiality codes beyond the base fixed code specified in the CDA document.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality', fhirVersion: 'R4')]
class CompositionOtherConfidentialityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Coding|null valueCoding Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $valueCoding = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/composition-clinicaldocument-otherConfidentiality',
		    value: $this->valueCoding,
		);
	}
}
