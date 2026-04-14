<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Community Based Collaborative Care
 * @see http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint
 * @description Endpoint for sending Disclosure notifications in the form of FHIR AuditEvent records.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint', fhirVersion: 'R4')]
class ConsentNotificationEndpointExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var UriPrimitive|null valueUri Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $valueUri = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/consent-NotificationEndpoint',
		    value: $this->valueUri,
		);
	}
}
