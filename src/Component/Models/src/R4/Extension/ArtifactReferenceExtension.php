<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/artifact-reference
 * @description A reference to a resource, canonical resource, or non-FHIR resource.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-reference', fhirVersion: 'R4')]
class ArtifactReferenceExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Reference|CanonicalPrimitive|UriPrimitive|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/artifact-reference',
		    value: $this->value,
		);
	}
}
