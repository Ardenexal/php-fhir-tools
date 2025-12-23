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
class FHIRSubstanceReferenceInformation extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Todo */
		public FHIRString|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceReferenceInformationGene> gene Todo */
		public array $gene = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceReferenceInformationGeneElement> geneElement Todo */
		public array $geneElement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceReferenceInformationClassification> classification Todo */
		public array $classification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceReferenceInformationTarget> target Todo */
		public array $target = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
