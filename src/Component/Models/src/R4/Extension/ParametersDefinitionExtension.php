<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/parameters-definition
 * @description This specifies the definition for a parameter if needed. This is useful for communicating the type of a parameter when the parameter has no value.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/parameters-definition', fhirVersion: 'R4')]
class ParametersDefinitionExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var ParameterDefinition|null valueParameterDefinition Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'ParameterDefinition', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ParameterDefinition $valueParameterDefinition = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/parameters-definition',
		    value: $this->valueParameterDefinition,
		);
	}
}
