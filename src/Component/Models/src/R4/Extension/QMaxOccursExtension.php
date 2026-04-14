<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs
 * @description The maximum number of times the group must appear, or the maximum number of answers for a question - when greater than 1 and not unlimited.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs', fhirVersion: 'R4')]
class QMaxOccursExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-maxOccurs',
		    value: $this->valueInteger,
		);
	}
}
