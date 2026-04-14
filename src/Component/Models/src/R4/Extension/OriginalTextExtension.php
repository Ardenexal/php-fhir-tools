<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/originalText
 * @description A human language representation of the concept (resource/element) as seen/selected/uttered by the user who entered the data and/or which represents the full intended meaning of the user. This can be provided either directly as text, or as a url that is a reference to a portion of the narrative of a resource ([DomainResource.text](narrative.html) or [Composition.section.text](composition-definitions.html#Composition.section.text)). When it a url, the value should end with a #{id} where the id identifies a specific portion of the referenced content (via an XHTML id attribute).  To provide human language maintained as part of the narrative, use [narrativeLink](StructureDefinition-narrativeLink.html).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/originalText', fhirVersion: 'R4')]
class OriginalTextExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var StringPrimitive|UrlPrimitive|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive|null $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/originalText',
		    value: $value,
		);
	}
}
