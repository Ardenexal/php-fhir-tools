<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 * @see http://hl7.org/fhir/StructureDefinition/cqf-certainty
 * @description An assessment of certainty, confidence, or quality of the item on which it appears.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-certainty', fhirVersion: 'R4')]
class CQFCertaintyExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var StringPrimitive|null description Textual description of certainty */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $description = null,
		/** @var array<Annotation> note Footnotes and/or explanatory notes */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Annotation', propertyKind: 'complex', isArray: true)]
		public array $note = [],
		/** @var CodeableConcept|null type Aspect of certainty being rated */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var CodeableConcept|null rating Assessment or judgement of the aspect */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $rating = null,
		/** @var StringPrimitive|null rater Who provided the rating */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $rater = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->description !== null) {
		    $subExtensions[] = new Extension(url: 'description', value: $this->description);
		}
		foreach ($this->note as $v) {
		    $subExtensions[] = new Extension(url: 'note', value: $v);
		}
		if ($this->type !== null) {
		    $subExtensions[] = new Extension(url: 'type', value: $this->type);
		}
		if ($this->rating !== null) {
		    $subExtensions[] = new Extension(url: 'rating', value: $this->rating);
		}
		if ($this->rater !== null) {
		    $subExtensions[] = new Extension(url: 'rater', value: $this->rater);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/cqf-certainty',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$description = null;
		$note = [];
		$type = null;
		$rating = null;
		$rater = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
		        $description = $ext->value;
		    }
		    if ($extUrl === 'note' && $ext->value instanceof Annotation) {
		        $note[] = $ext->value;
		    }
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		    if ($extUrl === 'rating' && $ext->value instanceof CodeableConcept) {
		        $rating = $ext->value;
		    }
		    if ($extUrl === 'rater' && $ext->value instanceof StringPrimitive) {
		        $rater = $ext->value;
		    }
		}

		return new static($description, $note, $type, $rating, $rater, $id);
	}
}
