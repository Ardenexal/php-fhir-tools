<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/confidential
 * @description Expresses the access policy associated with the element.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/confidential', fhirVersion: 'R4')]
class ConfidentialExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var UriPrimitive|CodeableConcept|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/confidential',
		    value: $this->value,
		);
	}
}
