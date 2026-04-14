<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/referencesContained
 * @description This indicates that the element containing this extension has content that makes reference to the specified contained resource.
 * * Expression may contain CQL or FHIRPath that makes reference to ValueSets
 * * string may contain FHIRPath referencing ValueSets or contain escaped XHTML referencing images (as Binaries)
 * * Narrative contains 'text' which is xhtml (and can't have extensions itself) which can also refer to images
 * * markdown can refer to images.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/referencesContained', fhirVersion: 'R4')]
class ReferencesContainedExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Reference|null valueReference Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $valueReference = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/referencesContained',
		    value: $this->valueReference,
		);
	}
}
