<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-knowledgeCapability
 * @description Defines a knowledge capability afforded by this knowledge artifact: Shareable, Publishable, Computable, and Executable, a scheme for organizing categories of functionality based on the content management lifecycle. See the Canonical Resource Management Infrastructure implementation guide for more information on these capability categories.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-knowledgeCapability', fhirVersion: 'R4')]
class CQFKnowledgeCapabilityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodePrimitive|null valueCode Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $valueCode = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-knowledgeCapability',
		    value: $this->valueCode,
		);
	}
}
