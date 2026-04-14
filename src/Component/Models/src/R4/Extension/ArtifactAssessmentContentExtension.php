<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/artifactassessment-content
 * @description A Content containing additional documentation, a review Content, usage guidance, or other relevant information from a particular user.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-content', fhirVersion: 'R4')]
class ArtifactAssessmentContentExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodePrimitive|null informationType comment | classifier | rating | container | response | change-request */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $informationType = null,
		/** @var MarkdownPrimitive|null summary Brief summary of the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $summary = null,
		/** @var CodeableConcept|null type Indicates what type of content this component represents */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var array<CodeableConcept> classifier Rating, classifier, or assessment */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $classifier = [],
		/** @var Quantity|null quantity Quantitative rating */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var Reference|null author Who authored the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $author = null,
		/** @var array<UriPrimitive> path What the comment is directed to */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
		public array $path = [],
		/** @var array<RelatedArtifact> relatedArtifact Additional information */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'RelatedArtifact', propertyKind: 'complex', isArray: true)]
		public array $relatedArtifact = [],
		/** @var bool|null freeToShare Acceptable to publicly share the resource content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $freeToShare = null,
		/** @var array<Base64BinaryPrimitive> component Contained content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
		public array $component = [],
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->informationType !== null) {
		    $subExtensions[] = new Extension(url: 'informationType', value: $this->informationType);
		}
		if ($this->summary !== null) {
		    $subExtensions[] = new Extension(url: 'summary', value: $this->summary);
		}
		if ($this->type !== null) {
		    $subExtensions[] = new Extension(url: 'type', value: $this->type);
		}
		foreach ($this->classifier as $v) {
		    $subExtensions[] = new Extension(url: 'classifier', value: $v);
		}
		if ($this->quantity !== null) {
		    $subExtensions[] = new Extension(url: 'quantity', value: $this->quantity);
		}
		if ($this->author !== null) {
		    $subExtensions[] = new Extension(url: 'author', value: $this->author);
		}
		foreach ($this->path as $v) {
		    $subExtensions[] = new Extension(url: 'path', value: $v);
		}
		foreach ($this->relatedArtifact as $v) {
		    $subExtensions[] = new Extension(url: 'relatedArtifact', value: $v);
		}
		if ($this->freeToShare !== null) {
		    $subExtensions[] = new Extension(url: 'freeToShare', value: $this->freeToShare);
		}
		foreach ($this->component as $v) {
		    $subExtensions[] = new Extension(url: 'component', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-content',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$informationType = null;
		$summary = null;
		$type = null;
		$classifier = [];
		$quantity = null;
		$author = null;
		$path = [];
		$relatedArtifact = [];
		$freeToShare = null;
		$component = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'informationType' && $ext->value instanceof CodePrimitive) {
		        $informationType = $ext->value;
		    }
		    if ($extUrl === 'summary' && $ext->value instanceof MarkdownPrimitive) {
		        $summary = $ext->value;
		    }
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		    if ($extUrl === 'classifier' && $ext->value instanceof CodeableConcept) {
		        $classifier[] = $ext->value;
		    }
		    if ($extUrl === 'quantity' && $ext->value instanceof Quantity) {
		        $quantity = $ext->value;
		    }
		    if ($extUrl === 'author' && $ext->value instanceof Reference) {
		        $author = $ext->value;
		    }
		    if ($extUrl === 'path' && $ext->value instanceof UriPrimitive) {
		        $path[] = $ext->value;
		    }
		    if ($extUrl === 'relatedArtifact' && $ext->value instanceof RelatedArtifact) {
		        $relatedArtifact[] = $ext->value;
		    }
		    if ($extUrl === 'freeToShare' && is_bool($ext->value)) {
		        $freeToShare = $ext->value;
		    }
		    if ($extUrl === 'component' && $ext->value instanceof Base64BinaryPrimitive) {
		        $component[] = $ext->value;
		    }
		}

		return new static($informationType, $summary, $type, $classifier, $quantity, $author, $path, $relatedArtifact, $freeToShare, $component, $id);
	}
}
