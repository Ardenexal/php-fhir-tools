<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceReferenceInformation
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubstanceReferenceInformation',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/SubstanceReferenceInformation',
	fhirVersion: 'R4',
)]
class SubstanceReferenceInformationResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string comment Todo */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationGene> gene Todo */
		public array $gene = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationGeneElement> geneElement Todo */
		public array $geneElement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationClassification> classification Todo */
		public array $classification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationTarget> target Todo */
		public array $target = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
