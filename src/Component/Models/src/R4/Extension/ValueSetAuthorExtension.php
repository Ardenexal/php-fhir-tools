<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/valueset-author
 * @description The entity or set of entities that create and may modify the Value Set Definition content. The name of a group or an individual, along with contact details.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-author', fhirVersion: 'R4')]
class ValueSetAuthorExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var ContactDetail|null valueContactDetail Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'ContactDetail', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail $valueContactDetail = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/valueset-author',
		    value: $this->valueContactDetail,
		);
	}
}
