<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Genomics
 * @see http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation
 * @description Allows capturing risk-relevant observations about the relative that aren't themselves a specific health condition; e.g. Certain ethnic ancestries that are disease-relevant, presence of particular genetic markers, etc.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation', fhirVersion: 'R4')]
class FMHObservationExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var Reference|null valueReference Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $valueReference = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation',
		    value: $this->valueReference,
		);
	}
}
