<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Security
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-Encrypted
 * @description True or False indicating whether the data was encrypted.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-Encrypted', fhirVersion: 'R4')]
class AEEncryptedExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var bool|null valueBoolean Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $valueBoolean = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/auditevent-Encrypted',
		    value: $this->valueBoolean,
		);
	}
}
