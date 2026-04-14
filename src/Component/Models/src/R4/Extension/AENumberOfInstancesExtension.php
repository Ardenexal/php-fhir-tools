<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Security
 * @see http://hl7.org/fhir/StructureDefinition/auditevent-NumberOfInstances
 * @description The Number of SOP Instances referred to by this entity.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/auditevent-NumberOfInstances', fhirVersion: 'R4')]
class AENumberOfInstancesExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://hl7.org/fhir/StructureDefinition/auditevent-NumberOfInstances',
		    value: $this->valueInteger,
		);
	}
}
