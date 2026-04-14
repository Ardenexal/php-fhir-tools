<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/patient-preferredPharmacy
 * @description The pharmacies that are preferred for dispensing prescribed medications.  A patient might prefer their local pharmacy.  A provider in a given practice may prefer a pharmacy near their clinic.  Or facilities may have a preference to use an in-house pharmacy.  These preferences may be represented using this extension on several resource types, and are expected to be inputs into a process that determines which pharmacy is ultimately used to dispense a medication.  That process may include considering drug formularies, pharmacy operating hours, pharmacy inventory, practitioner preferences, patient preferences, etc.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-preferredPharmacy', fhirVersion: 'R4')]
class PatientPreferredPharmacyExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var Reference pharmacy Preferred pharmacy for dispensing prescribed medications */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $pharmacy,
		/** @var CodeableConcept type Category of pharmacy */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'pharmacy', value: $this->pharmacy);
		$subExtensions[] = new Extension(url: 'type', value: $this->type);
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/patient-preferredPharmacy',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$pharmacy = null;
		$type = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'pharmacy' && $ext->value instanceof Reference) {
		        $pharmacy = $ext->value;
		    }
		    if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
		        $type = $ext->value;
		    }
		}

		return new static($pharmacy, $type, $id);
	}
}
