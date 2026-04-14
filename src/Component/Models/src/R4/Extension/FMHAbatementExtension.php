<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Care
 * @see http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement
 * @description The approximate date, age, or flag indicating that the condition of the family member resolved. The abatement should only be specified if the condition is stated in the positive sense, i.e., the didNotHave flag is false.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement', fhirVersion: 'R4')]
class FMHAbatementExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var DatePrimitive|Age|bool|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Age|bool|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/familymemberhistory-abatement',
		    value: $this->value,
		);
	}
}
