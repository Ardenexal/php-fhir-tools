<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Security
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID
 * @description An AlternativeUserID Number associated with this participant object.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID', fhirVersion: 'R4')]
class AEAlternativeUserIDExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Identifier|null valueIdentifier Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $valueIdentifier = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/auditevent-AlternativeUserID',
		    value: $this->valueIdentifier,
		);
	}
}
