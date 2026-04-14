<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender
 * @description Recorded sex or gender (RSG) information includes the various sex and gender concepts that are often used in existing systems but are known NOT to represent a gender identity, sex parameter for clinical use, or attributes related to sexuality, such as sexual orientation, sexual activity, or sexual attraction. Examples of recorded sex or gender concepts include administrative gender, administrative sex, and sex assigned at birth.  When exchanging this concept, refer to the guidance in the [Gender Harmony Implementation Guide](http://hl7.org/xprod/ig/uv/gender-harmony/).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender', fhirVersion: 'R4')]
class RecordedSexOrGenderExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept valueSlice The recorded sex or gender property for the individual */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueSlice,
		/** @var CodeableConcept|null type Type of recorded sex or gender. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var Period|null effectivePeriod When the recorded sex or gender value applies */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $effectivePeriod = null,
		/** @var DateTimePrimitive|null acquisitionDate When the sex or gender value was recorded. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $acquisitionDate = null,
		/** @var CodeableConcept|null source The source of the Recorded Sex or Gender value. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $source = null,
		/** @var CodeableConcept|null sourceDocument The document the sex or gender property was acquired from. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $sourceDocument = null,
		/** @var StringPrimitive|null sourceField The name of the field within the source document where this sex or gender property is initially recorded. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $sourceField = null,
		/** @var CodeableConcept|null jurisdiction Who issued the document where the sex or gender was aquired */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $jurisdiction = null,
		/** @var StringPrimitive|null comment Context or source information about the recorded sex or gender */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $comment = null,
		/** @var bool|null genderElementQualifier Whether this recorded sex or gender value qualifies the .gender element. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $genderElementQualifier = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
		if ($this->type !== null) {
		    $subExtensions[] = new Extension(url: 'type', value: $this->type);
		}
		if ($this->effectivePeriod !== null) {
		    $subExtensions[] = new Extension(url: 'effectivePeriod', value: $this->effectivePeriod);
		}
		if ($this->acquisitionDate !== null) {
		    $subExtensions[] = new Extension(url: 'acquisitionDate', value: $this->acquisitionDate);
		}
		if ($this->source !== null) {
		    $subExtensions[] = new Extension(url: 'source', value: $this->source);
		}
		if ($this->sourceDocument !== null) {
		    $subExtensions[] = new Extension(url: 'sourceDocument', value: $this->sourceDocument);
		}
		if ($this->sourceField !== null) {
		    $subExtensions[] = new Extension(url: 'sourceField', value: $this->sourceField);
		}
		if ($this->jurisdiction !== null) {
		    $subExtensions[] = new Extension(url: 'jurisdiction', value: $this->jurisdiction);
		}
		if ($this->comment !== null) {
		    $subExtensions[] = new Extension(url: 'comment', value: $this->comment);
		}
		if ($this->genderElementQualifier !== null) {
		    $subExtensions[] = new Extension(url: 'genderElementQualifier', value: $this->genderElementQualifier);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/individual-recordedSexOrGender',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$valueSlice = null;
		$type = null;
		$effectivePeriod = null;
		$acquisitionDate = null;
		$source = null;
		$sourceDocument = null;
		$sourceField = null;
		$jurisdiction = null;
		$comment = null;
		$genderElementQualifier = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
		        $valueSlice = $ext->value;
		    }
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		    if ($extUrl === 'effectivePeriod' && $ext->value instanceof Period) {
		        $effectivePeriod = $ext->value;
		    }
		    if ($extUrl === 'acquisitionDate' && $ext->value instanceof DateTimePrimitive) {
		        $acquisitionDate = $ext->value;
		    }
		    if ($extUrl === 'source' && $ext->value instanceof CodeableConcept) {
		        $source = $ext->value;
		    }
		    if ($extUrl === 'sourceDocument' && $ext->value instanceof CodeableConcept) {
		        $sourceDocument = $ext->value;
		    }
		    if ($extUrl === 'sourceField' && $ext->value instanceof StringPrimitive) {
		        $sourceField = $ext->value;
		    }
		    if ($extUrl === 'jurisdiction' && $ext->value instanceof CodeableConcept) {
		        $jurisdiction = $ext->value;
		    }
		    if ($extUrl === 'comment' && $ext->value instanceof StringPrimitive) {
		        $comment = $ext->value;
		    }
		    if ($extUrl === 'genderElementQualifier' && is_bool($ext->value)) {
		        $genderElementQualifier = $ext->value;
		    }
		}

		return new static($valueSlice, $type, $effectivePeriod, $acquisitionDate, $source, $sourceDocument, $sourceField, $jurisdiction, $comment, $genderElementQualifier, $id);
	}
}
