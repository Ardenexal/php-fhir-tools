<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-xml-type
 * @description The XML (schema) type of a property - used for the value attribute of a primitive type (for which there is no type in the FHIR typing system).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-xml-type', fhirVersion: 'R4')]
class XmlTypeExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var StringPrimitive|null valueString Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $valueString = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-xml-type',
		    value: $this->valueString,
		);
	}
}
