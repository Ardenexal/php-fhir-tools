<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A component comment, classifier, or rating of the artifact.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ArtifactAssessment', elementPath: 'ArtifactAssessment.content', fhirVersion: 'R5')]
class FHIRArtifactAssessmentContent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentInformationTypeType informationType comment | classifier | rating | container | response | change-request */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRArtifactAssessmentInformationTypeType $informationType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown summary Brief summary of the content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $summary = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type What type of content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> classifier Rating, classifier, or assessment */
		public array $classifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity Quantitative rating */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference author Who authored the content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $author = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri> path What the comment is directed to */
		public array $path = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact> relatedArtifact Additional information */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean freeToShare Acceptable to publicly share the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $freeToShare = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRArtifactAssessmentContent> component Contained content */
		public array $component = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
