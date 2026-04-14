<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Quality Information
 * @see http://hl7.org/fhir/StructureDefinition/cqf-improvementNotationGuidance
 * @description Narrative text to explain the improvement notation and how to interpret it.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-improvementNotationGuidance', fhirVersion: 'R4')]
class CQFImprovementNotationGuidanceExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var MarkdownPrimitive|null valueMarkdown Value of extension */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $valueMarkdown = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-improvementNotationGuidance',
		    value: $this->valueMarkdown,
		);
	}
}
