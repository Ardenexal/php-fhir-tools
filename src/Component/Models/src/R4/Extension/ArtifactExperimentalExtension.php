<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/artifact-experimental
 * @description A Boolean value to indicate that this artifact is authored for testing purposes (or education/evaluation/marketing) and is not intended to be used for genuine usage.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-experimental', fhirVersion: 'R4')]
class ArtifactExperimentalExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var bool|null valueBoolean Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $valueBoolean = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/artifact-experimental',
		    value: $this->valueBoolean,
		);
	}
}
