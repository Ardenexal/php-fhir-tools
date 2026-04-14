<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm
 * @description Indicates the mechanism used to compare versions to determine which is more current.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm', fhirVersion: 'R4')]
class ArtifactVersionAlgorithmExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var StringPrimitive|Coding|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|null $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/artifact-versionAlgorithm',
		    value: $value,
		);
	}
}
