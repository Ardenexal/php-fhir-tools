<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/procedure-directedBy
 * @description The target of the extension is a distinct actor from the requester and has decision-making authority over the service and takes direct responsibility to manage the service.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-directedBy', fhirVersion: 'R4')]
class PRDirectedByExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodeableConcept|Reference|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/procedure-directedBy',
		    value: $this->value,
		);
	}
}
