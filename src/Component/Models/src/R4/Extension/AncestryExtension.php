<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry
 * @description Ancestry information.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry', fhirVersion: 'R4')]
class AncestryExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept name Ancestry name */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $name,
		/** @var string|null percentage Ancestry percentage */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?string $percentage = null,
		/** @var CodeableConcept|null source Source of ancestry report */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $source = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'Name', value: $this->name);
		if ($this->percentage !== null) {
		    $subExtensions[] = new Extension(url: 'Percentage', value: $this->percentage);
		}
		if ($this->source !== null) {
		    $subExtensions[] = new Extension(url: 'Source', value: $this->source);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$name = null;
		$percentage = null;
		$source = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'Name' && $ext->value instanceof CodeableConcept) {
		        $name = $ext->value;
		    }
		    if ($extUrl === 'Percentage' && is_string($ext->value)) {
		        $percentage = $ext->value;
		    }
		    if ($extUrl === 'Source' && $ext->value instanceof CodeableConcept) {
		        $source = $ext->value;
		    }
		}

		return new static($name, $percentage, $source, $id);
	}
}
