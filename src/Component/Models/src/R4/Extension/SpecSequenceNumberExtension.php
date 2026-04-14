<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/specimen-sequenceNumber
 * @description An assigned number on the specimen denoting the order of collection.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/specimen-sequenceNumber', fhirVersion: 'R4')]
class SpecSequenceNumberExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var int|null valueInteger Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
		public ?int $valueInteger = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/specimen-sequenceNumber',
		    value: $this->valueInteger,
		);
	}
}
