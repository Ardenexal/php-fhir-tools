<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/rendering-xhtml
 * @description This is an equivalent of the string on which the extension is sent, but includes additional XHTML markup, such as bold, italics, styles, tables, etc. Existing [restrictions on XHTML content](narrative.html#security) apply. Note that using [markdown](StructureDefinition-rendering-markdown.html) allows for greater flexibility of display. Like the [Resource Narrative](narrative.html), this extension may reference binary resources for image content (see note about [referencing images](narrative.html#id)).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/rendering-xhtml', fhirVersion: 'R4')]
class XhtmlRepresentationExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://hl7.org/fhir/StructureDefinition/rendering-xhtml',
		    value: $this->valueString,
		);
	}
}
