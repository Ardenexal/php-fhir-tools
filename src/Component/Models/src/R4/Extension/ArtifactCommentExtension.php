<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-artifactComment
 * @description A comment containing additional documentation, a review comment, usage guidance, or other relevant information from a particular user.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-artifactComment', fhirVersion: 'R4')]
class ArtifactCommentExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodePrimitive type documentation | review | guidance */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $type,
		/** @var MarkdownPrimitive text The comment */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $text,
		/** @var array<UriPrimitive> target What the comment is directed to */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
		public array $target = [],
		/** @var array<UriPrimitive> reference Supporting reference for the comment */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
		public array $reference = [],
		/** @var StringPrimitive|null user Who commented */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $user = null,
		/** @var DateTimePrimitive|null authoredOn Date and time the comment was made */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $authoredOn = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'type', value: $this->type);
		$subExtensions[] = new Extension(url: 'text', value: $this->text);
		foreach ($this->target as $v) {
		    $subExtensions[] = new Extension(url: 'target', value: $v);
		}
		foreach ($this->reference as $v) {
		    $subExtensions[] = new Extension(url: 'reference', value: $v);
		}
		if ($this->user !== null) {
		    $subExtensions[] = new Extension(url: 'user', value: $this->user);
		}
		if ($this->authoredOn !== null) {
		    $subExtensions[] = new Extension(url: 'authoredOn', value: $this->authoredOn);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-artifactComment',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$type = null;
		$text = null;
		$target = [];
		$reference = [];
		$user = null;
		$authoredOn = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'type' && $ext->value instanceof CodePrimitive) {
		        $type = $ext->value;
		    }
		    if ($extUrl === 'text' && $ext->value instanceof MarkdownPrimitive) {
		        $text = $ext->value;
		    }
		    if ($extUrl === 'target' && $ext->value instanceof UriPrimitive) {
		        $target[] = $ext->value;
		    }
		    if ($extUrl === 'reference' && $ext->value instanceof UriPrimitive) {
		        $reference[] = $ext->value;
		    }
		    if ($extUrl === 'user' && $ext->value instanceof StringPrimitive) {
		        $user = $ext->value;
		    }
		    if ($extUrl === 'authoredOn' && $ext->value instanceof DateTimePrimitive) {
		        $authoredOn = $ext->value;
		    }
		}

		return new static($type, $text, $target, $reference, $user, $authoredOn, $id);
	}
}
