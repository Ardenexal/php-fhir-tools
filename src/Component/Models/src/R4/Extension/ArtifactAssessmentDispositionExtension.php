<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition
 * @description Possible values for the disposition of a comment or change request, typically used for comments and change requests, to indicate the disposition of the responsible party towards the changes suggested by the comment or change request.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition', fhirVersion: 'R4')]
class ArtifactAssessmentDispositionExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension
{
	public function __construct(
		/** @var CodePrimitive|null valueCode unresolved | not-persuasive | persuasive | persuasive-with-modification | not-persuasive-with-modification */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $valueCode = null,
		?string $id = null,
		array $extension = [],
	) {
		parent::__construct(
		    id: $id,
		    extension: $extension,
		    url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition',
		    value: $this->valueCode,
		);
	}
}
