<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/individual-genderIdentity
 * @description An individual's personal sense of being a man, woman, boy, girl, nonbinary, or something else. This represents an individual’s identity, ascertained by asking them what that identity is.
 *  In the case where the gender identity is communicated by a third party, for example, if a spouse indicates the gender identity of their partner on an intake form, a Provenance resource can be used with a Provenance.target referring to the Patient, with a targetElement extension identifying the gender identity extension as the target element within the Patient resource.  When exchanging this concept, refer to the guidance in the [Gender Harmony Implementation Guide](http://hl7.org/xprod/ig/uv/gender-harmony/).
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity', fhirVersion: 'R4')]
class PGenderIdentityExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept valueSlice The individual's gender identity */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueSlice,
		/** @var Period|null period The time period during which the gender identity applies to the individual */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var StringPrimitive|null comment Text to further explain the use of the specified gender identity */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $comment = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
		if ($this->period !== null) {
		    $subExtensions[] = new Extension(url: 'period', value: $this->period);
		}
		if ($this->comment !== null) {
		    $subExtensions[] = new Extension(url: 'comment', value: $this->comment);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity',
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
		$period = null;
		$comment = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'value' && $ext->value instanceof CodeableConcept) {
		        $valueSlice = $ext->value;
		    }
		    if ($extUrl === 'period' && $ext->value instanceof Period) {
		        $period = $ext->value;
		    }
		    if ($extUrl === 'comment' && $ext->value instanceof StringPrimitive) {
		        $comment = $ext->value;
		    }
		}

		return new static($valueSlice, $period, $comment, $id);
	}
}
