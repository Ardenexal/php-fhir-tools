<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/address-official
 * @description Indicate that this address is meant to be the 'official' address for that person. What an 'official' address is depends on the country. This extension allows to specify if this address is or it is not the official address, or to indicate that this is the official address for that country.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/address-official', fhirVersion: 'R4')]
class OfficialAddressExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var bool|CodeableConcept|null value Value of extension (bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?bool $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/address-official',
		    value: $this->value,
		);
	}
}
