<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/individual-pronouns
 * @description The pronouns to use when referring to an individual in verbal or written communication.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/individual-pronouns', fhirVersion: 'R4')]
class PronounsExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept valueSlice The individual's pronouns */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $valueSlice,
		/** @var Period|null period When the pronouns apply to the individual */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var StringPrimitive|null comment Explaination about the use of the pronouns */
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
		    url: 'http://hl7.org/fhir/StructureDefinition/individual-pronouns',
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
