<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/valueset-supplement
 * @description This extension declares that a value set depends on a particular [CodeSystem supplement](codesystem.html#supplements) and should not be used in its absence.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-supplement', fhirVersion: 'R4')]
class VSSupplementExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
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
		    url: 'http://hl7.org/fhir/StructureDefinition/valueset-supplement',
		    value: $this->valueCanonical,
		);
	}
}
