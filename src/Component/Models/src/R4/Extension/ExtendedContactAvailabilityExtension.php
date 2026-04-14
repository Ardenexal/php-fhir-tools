<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/extended-contact-availability
 * @description The details provided in this contact may be used according to the attached availability guidelines.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability', fhirVersion: 'R4')]
class ExtendedContactAvailabilityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Availability|null valueAvailability Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Availability', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Availability $valueAvailability = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/extended-contact-availability',
		    value: $this->valueAvailability,
		);
	}
}
