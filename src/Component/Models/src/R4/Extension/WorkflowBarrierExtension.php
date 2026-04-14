<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/workflow-barrier
 * @description Any obstacle that limits or prevents obtaining care.  Barriers in health and social care include, but are not limited to, physical barriers, psychological barriers, physiological barriers, financial barriers, geographical barriers, cultural/language barriers and resource barriers.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-barrier', fhirVersion: 'R4')]
class WorkflowBarrierExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodeableReference|null valueCodeableReference Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableReference $valueCodeableReference = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/workflow-barrier',
		    value: $this->valueCodeableReference,
		);
	}
}
