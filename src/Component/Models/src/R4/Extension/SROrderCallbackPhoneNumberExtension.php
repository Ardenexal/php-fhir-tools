<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number
 * @description This extension contains the phone number for reporting a status or a result. This is represented in v2 as OBR-17 Order Callback Phone Number ID 00250.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number', fhirVersion: 'R4')]
class SROrderCallbackPhoneNumberExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var ContactPoint|null valueContactPoint Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'ContactPoint', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint $valueContactPoint = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-order-callback-phone-number',
		    value: $this->valueContactPoint,
		);
	}
}
