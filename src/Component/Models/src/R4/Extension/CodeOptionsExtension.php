<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/codeOptions
 * @description A logical reference (i.e. a reference to ValueSet.url) to a value set (and optionally a version) that identifies a set of possible coded values for the element. This extension is used to convey a list of candidate codes when there is no formal code in the code system already defined that captures the intended set. For example, the concept of COVID preventative medications can be expressed as a value set because there is no specific code representing that concept.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codeOptions', fhirVersion: 'R4')]
class CodeOptionsExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CanonicalPrimitive|null valueCanonical Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $valueCanonical = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/codeOptions',
		    value: $this->valueCanonical,
		);
	}
}
