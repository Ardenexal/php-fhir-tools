<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Orders and Observations
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsAminoAcidChange
 * @description AminoAcidChange information.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAminoAcidChange', fhirVersion: 'R4')]
class AminoAcidChangeExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept|null name HGVS nomenclature for observed Amino Acid Change */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $name = null,
		/** @var CodeableConcept|null type Amino Acid Change Type */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->name !== null) {
		    $subExtensions[] = new Extension(url: 'Name', value: $this->name);
		}
		if ($this->type !== null) {
		    $subExtensions[] = new Extension(url: 'Type', value: $this->type);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAminoAcidChange',
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
		$type = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'Name' && $ext->value instanceof CodeableConcept) {
		        $name = $ext->value;
		    }
		    if ($extUrl === 'Type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		}

		return new static($name, $type, $id);
	}
}
