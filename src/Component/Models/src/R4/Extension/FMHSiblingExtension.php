<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Clinical Genomics
 * @see http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling
 * @description Identifies a sibling of the relative.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling', fhirVersion: 'R4')]
class FMHSiblingExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept type sibling | brother | sister | etc. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type,
		/** @var Reference reference Link to sibling relative(s) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reference,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'type', value: $this->type);
		$subExtensions[] = new Extension(url: 'reference', value: $this->reference);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling',
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
		$reference = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		    if ($extUrl === 'reference' && $ext->value instanceof Reference) {
		        $reference = $ext->value;
		    }
		}

		return new static($type, $reference, $id);
	}
}
