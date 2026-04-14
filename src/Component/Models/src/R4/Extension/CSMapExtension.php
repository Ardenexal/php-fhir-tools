<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-map
 * @description A reference to a concept map that is relevant for the interpretation of this value set.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-map', fhirVersion: 'R4')]
class CSMapExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CanonicalPrimitive|null valueCanonical Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $valueCanonical = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/codesystem-map',
		    value: $this->valueCanonical,
		);
	}
}
