<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice
 * @description Mark that an invariant represents 'best practice' rule - a rule that implementers may choose to enforce at error level in some or all circumstances.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice', fhirVersion: 'R4')]
class BestPracticeExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var bool|CodeableConcept|null value Value of extension (bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice',
		    value: $value,
		);
	}
}
