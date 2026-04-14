<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-defaultValue
 * @description Provides a default value for a parameter definition.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-defaultValue', fhirVersion: 'R4')]
class DefaultValueExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var StringPrimitive|bool|int|string|DatePrimitive|DateTimePrimitive|Coding|CodeableConcept|Period|Range|Quantity|Ratio|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|bool|int|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|null) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $value = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-defaultValue',
		    value: $this->value,
		);
	}
}
