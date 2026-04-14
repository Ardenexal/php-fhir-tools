<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / Patient Administration
 * @see http://hl7.org/fhir/StructureDefinition/patient-citizenship
 * @description The patient's legal status as citizen of a country.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-citizenship', fhirVersion: 'R4')]
class PatCitizenshipExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var CodeableConcept|null code Nation code of citizenship */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var Period|null period Time period of citizenship */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		?string $id = null,
	) {
		$subExtensions = [];
		if ($this->code !== null) {
		    $subExtensions[] = new Extension(url: 'code', value: $this->code);
		}
		if ($this->period !== null) {
		    $subExtensions[] = new Extension(url: 'period', value: $this->period);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/patient-citizenship',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$code = null;
		$period = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'code' && $ext->value instanceof CodeableConcept) {
		        $code = $ext->value;
		    }
		    if ($extUrl === 'period' && $ext->value instanceof Period) {
		        $period = $ext->value;
		    }
		}

		return new static($code, $period, $id);
	}
}
